<?php
            global $bloqueo;
            $bloqueo = 0;//Esta bloquedo por default
            $referer = $_SERVER['HTTP_REFERER'];
            $current_page = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $referer = explode("?c=",$referer);
            $current_page = explode("?c=",$current_page);

            //Si el hace referencia desde el menu te desbloquea, sino busca que la subcadena del referer coincida con el de la url actual
           if(strpos($_SERVER['HTTP_REFERER'],"accelog/menu.php"))
                $bloqueo = 0;
            elseif($referer[0] == $current_page[0])
                $bloqueo = 0;

            //echo "<br />---------<br />".$referer[0];
            //echo "<br />---------<br />".$current_page[0];
?>