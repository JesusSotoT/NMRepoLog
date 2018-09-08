<?php
echo $hola;
?>
<table>
    <tr><th>Id</th><th>Nombre</th><th>Cerrado?</th></tr>
    <?php
    while($l = $lista->fetch_object())
    {
        $cerrado ='no';
        if($l->Cerrado) $cerrado ='si';
        echo "<tr><td>$l->Id</td><td>$l->NombreEjercicio</td><td>$cerrado</td></tr>";
    }
    ?>
</table>
<br />
La Cuenta seleccionada es:<?php echo $NombreCuenta; ?>