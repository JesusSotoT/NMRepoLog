<?php
	include('../../netwarelog/webconfig.php');
	include "../../netwarelog/catalog/conexionbd.php";
	$conexion->cerrar();

	include "../../modulos/hazbizne/clases.php";
	$ejecutivo = $_GET['ejecutivo'];

	$netwarstorep = new clnetwarstore_p();
	$distribuidores = $netwarstorep->get_distribuidores($ejecutivo);

    if (count($distribuidores) > 0){
        foreach ($distribuidores as $key => $value) {
            ?>
            <option value="<?php echo $value["intId"]?>"><?php echo $value["strName"]?></option>
            <?php
        }
    }
    else {
        ?>
        <option value="null">--No existen distribuidores--</option>
        <?php
    }

    /*
    while($row = mysql_fetch_array($query)) {
    echo "<option value='$row[id]'>$row[subcategory_name]</option>";
    }*/
     
?>