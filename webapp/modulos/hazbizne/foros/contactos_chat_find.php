<?php
    include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();
    
    include "../clases.php";
    
    $nmdev_commun = new clnmdev_common();
    $conversaciones = $nmdev_commun->get_contactos_chat($bd);
    //$nmdev_commun->disconnect();
    
    
    $netwarstore = new clnetwarstore();
    $conversaciones_link = $netwarstore->get_contactos_chat_info($conversaciones);
    $netwarstore->disconnect();
    
    
    
    foreach ($conversaciones_link as $conversacion_detalle) {
        $cantidad_mensajes_no_leidos = $nmdev_commun->get_mensajes_no_leidos($bd, $conversacion_detalle["bd"]);
        if ($cantidad_mensajes_no_leidos != 0)
                $texto = $conversacion_detalle["email"]." (".$cantidad_mensajes_no_leidos.")";
        else
                $texto = $conversacion_detalle["email"];
        ?>
            <div class="chat_contacto" onclick="linkcontactochat_click('<?php echo $conversacion_detalle["rfc"]?>','<?php echo $conversacion_detalle["bd"]?>');"><span><?php echo $texto?></span></div>
        <?php
    }

    $nmdev_commun->disconnect();

    /*
    foreach ($conversaciones as $conversacion) {
       ?>
            <div class="chat_contacto"><span><?php echo $conversacion["resultado"]?></span></div>
        <?php 
    }*/

?>
    <!--
    <div>
        <span>Buscando resultados para algo</span>
    </div>-->