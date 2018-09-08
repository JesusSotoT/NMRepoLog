<?php
/* */
ini_set("display_errors",1);
include('../../netwarelog/webconfig.php');

include "../../netwarelog/catalog/conexionbd.php";
$conexion->cerrar();

include "../../modulos/hazbizne/clases.php";
/*
$nmdev = new clnmdev();
$zonas = $nmdev->get_zonas();
$nmdev->disconnect();
*/

$netwarstorep = new clnetwarstore_p();
$licenses_count = $netwarstorep->get_licencias();
//$distribuidores = $netwarstorep->get_distribuidores();
//$zonas = $netwarstorep->get_zonas();
$ejecutivos = $netwarstorep->get_ejecutivos();
$netwarstorep->disconnect();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#ejecutivo").change(function() {
                //$(this).after('<div id="loader"><img src="img/loading.gif" alt="loading subcategory" /></div>');
                $.get('load_distribuidor.php?ejecutivo=' + $(this).val(), function(data) {
                    $("#distribuidor").html(data);
                    /*$('#loader').slideUp(200, function() {
                        $(this).remove();
                    });*/
                });
            });
        });
    </script>    
</head>
<body>
    <div class=" nmwatitles ">
        Licencias por distribuidor
    </div>
    <form action="licencias_valida.php" method="post">
        <div>
            <table>
                <tr>
                    <td>
                        <span>Ejecutivo</span>
                    </td>
                    <td>
                        <span>
                            <select name="zona" id="ejecutivo">
                                <option value="0">--Elija una opcion--</option>
                                <?php
                                foreach ($ejecutivos as $key => $value) {
                                    ?>
                                    <option value="<?php echo $value["intId"]?>"><?php echo $value["strName"]?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </span>      
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Distribuidor</span>
                    </td>
                    <td>
                        <span>
                            <select name="distribuidor" id="distribuidor">
                                <option value="null">--Seleccione un distribuidor--</option>
                            </select>
                        </span>
                    </td>
                </tr>
            </table>
        	<table>
        	<?php
        	foreach ($licenses_count as $key => $value) {
        		?>
        		<tr>
        		<td>
        		<?php
        		echo $value["appname"] . " (" . $value["salesman"] . ")";
        		$index = 0;
        		$top = (int)$value["numero"];
        		?>
        		</td>
        		<td>
        		<!--
                <select name="<?php echo $value["salesman"]?>">
        			<?php
        				while ($index <= $top){
        					?>
        					<option value="<?php echo $index?>"><?php echo $index?></option>
        					<?php
        					$index = $index + 1;
        				}
        			?>
        		</select>
                -->
                <input type="number" name="<?php echo $value["salesman"]?>" value="0" min="0" max="<?php echo $top?>"></input>
        		</td>
                <td>
                    <span>de <?php echo $top;?> disponibles</span>
                </td>
        		</tr>
        		<?php
        	}
        	?>
        	</table>
        </div>
        <div>
            <input type="submit" value="Asignar">
        </div>
    </form>
</body>
</html>