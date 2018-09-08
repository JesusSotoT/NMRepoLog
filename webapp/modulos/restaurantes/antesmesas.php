<?php
include("../../netwarlog/webconfig.php");

$enlace = mysql_connect($servidor, $usuariobd, $clavebd);
mysql_select_db($bd);

$resmeslic = mysql_query('SELECT valor from comun_parametros_licencias where parametro="Mesas"');

    while ($fila = mysql_fetch_array($resmeslic, MYSQL_NUM)) {
        $mesaslicencia = $fila[0];
    }

        $resmes = mysql_query('SELECT * from com_mesas where tipo=0');

         $mesasins = mysql_num_rows($resmes);

             if($mesasins == $mesaslicencia){
                $permiso = 0;
             }else{
                $permiso = 1;
             }
?>
<script src="js/jquery-1.10.2.min.js"></script>
<script>
    $(document).ready(function() {
        url = window.location.href;
        if(url.match(/a=1/) ){
            var permiso = "<?php echo $permiso; ?>";
                if(permiso==0){
                    alert('Ya no tienes mas mesas disponibles para registrar, si quieres mas contacta con tu distribuidor');
                     $("#send").hide();
                     $("#i1308").attr('disabled', true);
                     $("#i1309").attr('disabled', true);
                    return;
                }
        }
	
	// Cambia el tipo de campo texto por numero
        // $('#i1627').attr('type', 'number');
    });
</script>