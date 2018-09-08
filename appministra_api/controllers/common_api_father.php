<?php
    
    class CommonapiFather
    {

        //Genera el contenido cambiante, donde $f es la variable que contiene el nombre del controlador que va a cargar
        //si el controlador existe lo carga caso contrario lo que cargara sera un controlador por default que contiene
        //la pagina default principal
        function content($f)
        {   

            $current_page = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $per = 0; //Permiso por default NO
            global $bloqueo;
            
            if(strpos($current_page, "ajax.php?c=")) //Si la url es del ajax da acceso
            {
                $per = 1;
            }

            if($per) //Si tiene permisos entonces llama al metodo
            {
                if(isset($f))
                {
                    $this->$f();
                }
                else
                {
                    $this->mainPage();
                }
            }
            else
            {
                $this->noAccess();
            }
                  
        }

        function noAccess()
        {
            echo "<b style='color:red;'>No tienes acceso </b>";
        }
    }

?>