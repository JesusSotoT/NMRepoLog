<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
				ini_set('session.cookie_httponly',1);
        session_start();
        $_SESSION["accelog_idorganizacion"] = "";
        $_SESSION["accelog_nombre_organizacion"] = "";
        $_SESSION["accelog_idempleado"] = "";
        $_SESSION["accelog_login"] = "";
        $_SESSION["accelog_opciones"] = "";
        $_SESSION["accelog_superusu"] = "";
	
	if(isset($_SESSION["accelog_tabs"])){
			$tabs=$_SESSION["accelog_tabs"];
	} else {
			$tabs = null;
	}

	//Destruyendo las sesiones	
	session_destroy();
	if($tabs!=null){	
		foreach($tabs as $id){
			//error_log($id." cerrado");
			session_id($id);
			session_start();
			session_destroy();
		}
	}
	

?>
<html>
    <head>
        <title>Control de Acceso</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!--CSS-->
        <LINK href="css/estilo_accelog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--RECURSO LOCAL CSS-->

        
    </head>

    <body class="accelog">

        <script>
	   window.location = "index.php";
        </script>

    </body>

</html>
