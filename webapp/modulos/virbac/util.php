<?php  

    function requireMailer() {
        require_once(__DIR__.'/_dependences/PHPMailer/PHPMailerLoad.php');
        return $mail;
    }
	function fencripta($pwd, $salt) {
        $resultado = crypt($pwd, $salt);
        return $resultado;
    }

	function guardarUsuarioPortal($correoportal,$userportal,$passportal,$nombre){
        global $acceperfil, $accelogV, $idorg, $nombre_inst, $pdo;

        $accelog_salt = "$2a$07$".$accelogV."aaaaaaa$";
        $calve = fencripta($passportal, $accelog_salt);

        /*$sql = "SELECT idperfil from accelog_perfiles WHERE nombre='PORTALPROVEEDOR';";
        $res = $pdo->query($sql);

        if($res->rowCount()){
            $idperfil=$res['rows'][0]['idperfil'];
            $sqlx = "SELECT * from accelog_perfiles_me WHERE idperfil='$idperfil' AND idmenu='2407';";
            $resx = $pdo->query($sqlx);
            if( !$resx->rowCount() ){
                $sql = "INSERT INTO accelog_perfiles_me (idperfil, idmenu) values ('$idperfil','2407')";
                $pdo->query($sql);
            }            
        }else{
            $sql = "INSERT INTO accelog_perfiles (nombre, visible) values ('PORTALCLIENTE','-1')";
            $idperfil = $pdo->lastInsertId($sql);
             $sql = "INSERT INTO accelog_perfiles_me (idperfil, idmenu) values ('$idperfil','2407')";
            $pdo->query($sql);
        }*/


        $sql = "INSERT INTO empleados (nombre, apellido1, apellido2, idorganizacion, visible, administrador) values ('$nombre', '----', '----', '$idorg', '-1', 0)";
        $id_empleado = $pdo->lastInsertId($sql);

        $sql = "INSERT INTO accelog_usuarios (idempleado, usuario, clave, css) values ('$id_empleado', '$userportal', '$calve', 'default')";
        $pdo->query($sql);

        $sql = "INSERT INTO administracion_usuarios (nombre, apellidos, nombreusuario, clave, confirmaclave, correoelectronico, foto, idperfil, idempleado,  idSuc) values ('$nombre', '', '$userportal', '$passportal', '$passportal', '$correoportal', '', '$idperfil', '$id_empleado',  NULL)";
        $pdo->query($sql);

        $sql = "INSERT INTO accelog_usuarios_per (idperfil, idempleado) values ('$idperfil', '$id_empleado')";
        $pdo->query($sql);
    }
    function enviaCorreoPortal($correoportal,$userportal,$passportal,$nombre){
        global $nombre_inst, $mail;

        $h='<br>';
        $h.='<b>Url acceso:</b> <a href="http://'.$nombre_inst.'.netwarmonitor.mx">http://'.$nombre_inst.'.netwarmonitor.mx</a><br>';
        $h.='<b>Usuario:</b> '.$userportal.'<br>';
        $h.='<b>Contraseña:</b> '.$passportal.'<br>';

        $mail->From = "mailer@netwarmonitor.com";
        $mail->FromName = "NetwarMonitor";
        $mail->Subject = "Portal de Proveedores";
        $mail->AltBody = "Portal de Proveedores";
        $mail->MsgHTML($h);
        $mail->AddAddress($correoportal, $correoportal);

        if($mail->Send()){
            echo 1;
        }else{
            echo 0;
        }
    }

    

