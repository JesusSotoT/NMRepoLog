<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    $usuario = "";
    $contacto = "";
    $msg = "";
    $aNombres = array();
    $aEmails = array();
    $aIdEmp = array();

    if( isset($_GET) && !empty($_GET) ){
        
        define('link', $_GET['url']."modulos/notificaciones/confirmar_notificacion.php?idnotificacion=");
        
        include("clNotificaciones.php");
        include("../../netwarelog/catalog/conexionbd.php");

        $enviar_email = 1;
        $usuario = $_GET['usuario'];
        $contacto = $_GET['contacto'];
        $msg = $_GET['msg'];
		$sNotificacion = "";
        
        $sQuery = "Select idempleado, concat(nombre,' ',apellidop,' ',apellidom) nombre, correoelectronico email
                   From empleados Where (idempleado = $usuario) Limit 1;";
        $result = $conexion->consultar($sQuery);
        while($rs = $conexion->siguiente($result)){
            $aNombres[] = $rs{'nombre'};
            $aEmails[] = $rs{'email'};
            $aIdEmp[] = $rs{'idempleado'};
        }
		$conexion->cerrar_consulta($result);
		
        $sQuery = "Select idempleado, concat(nombre,' ',apellidop,' ',apellidom) nombre, correoelectronico email
                   From empleados Where (idempleado = $contacto) Limit 1;";
        $result = $conexion->consultar($sQuery);
        while($rs = $conexion->siguiente($result)){
            $aNombres[] = $rs{'nombre'};
            $aEmails[] = $rs{'email'};
            $aIdEmp[] = $rs{'idempleado'};
        }
        $conexion->cerrar_consulta($result);

        // Inicia la notificacion
        $sNotificacion = "El usuario ".$aNombres[0]." desea contactar con usted.";

        $notificaciones = new clNotificaciones();
        $enviar_email = $notificaciones->addNotif($usuario,$sNotificacion,42,14,link,0,date('Y-m-d H:i:s'),date('Y-m-d H:i:s'),$conexion);
        
        
        $email = $aEmails[1];

        /*------------------------------------------------+/
            Envio de email de notificacion y notificacion.
        /+------------------------------------------------*/

        if( $enviar_email > 0 ){
            
            $conexion->consultar("Update notificaciones Set link = '". link . $enviar_email."' Where idnotificacion = $enviar_email;");

            $conexion->consultar("Insert Into contactos (idempleadoanfitrion,idempleadoinvitado,aliasanfitrion,aliasinvitado,aceptado)
                                  Values(".$aIdEmp[0].",".$aIdEmp[1].",'".$aNombres[0]."','".$_GET['aliascontact']."',0)");
//                                  Values(".$aIdEmp[0].",".$aIdEmp[1].",'".$_GET['aliasuser']."','".$_GET['aliascontact']."',0)");

            $mail = $aEmails[0];
            $Msj = " Notificacion Polodesk. ";
            $asunto = $Msj;

            $mensaje  = "Informe: " . $Msj . " \r\n";
            $mensaje .= "<br>Este mensaje fue enviado por ".$_GET['aliasuser'];
            $mensaje .= "<br>e-mail de contacto: " . $mail . " \r\n ";
            $mensaje .= "<br>Enviado el " . date('d/m/Y') . " Hora:" . date('H:i:s');
            $mensaje .= "<br>Cc: ";
            $style_border = ''; 
            $bgcolor_celda_titulo = 'bgcolor="#FFFFFF"';
            $mensaje .= '
            <html>
            <head>
              <title>Tienes un nuevo mensaje a tu cuenta de correo : '.$email.'</title>
            </head>
            <body>
                <table name="Evento" width="65%" >
                    <tr><th '.$style_border.$bgcolor_celda_titulo.' align="center" colspan="">
                            Notificacion Polodesk
                        </th>
                    </tr>
                    <tr>
                        <td '.$style_border.$bgcolor_celda_titulo.' align="left">
                            Estimado '.$aNombres[1].'<br/>
                            '.$sNotificacion.'
                        </td>
                    </tr>
                    <tr>
                        <td '.$style_border.$bgcolor_celda_titulo.' align="left">
                            '.$msg.'
                        </td>
                    </tr>
                    <tr>
                        <td '.$style_border.$bgcolor_celda_titulo.' align="left">
                            <a href='. link . $enviar_email.' >Aceptar Contacto<img src='.$_GET['url'].'/modulos/registro/yes.png></a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="">
                        <a href="'.$_GET['url'].'">Se parte de Polodesk</a></td>
                    </tr>
                </table>
            </body>
            </html>
            ';
	
            $header = "From: soporte@netwaremonitor.com\r\n";
			$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
            // To send HTML mail, the Content-type header must be set
            $header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=utf-8\r\n";
			
            $para = ''.$email; // mail al cual van dirigidos los correos.

            ini_set("memory_limit",120);  // 2010-11-04 Esto indica archivos de 120 Mb limite	
			//mail($para, $asunto, $mensaje, $header);
            if( mail($para, $asunto, $mensaje, $header) ){
                    echo 'Email enviado a '.$para.' ...';
            }else{
                    echo 'Error de envio de email ...';
            }
        }
    }
?>
