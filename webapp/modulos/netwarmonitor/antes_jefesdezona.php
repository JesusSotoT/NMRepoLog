<script>

    //### BEGIN - MODULO DE CATALOGO UNICO DE PAIS/ESTADO/MUNICIPIO/COLONIA/CODIGO POSTAL ###
    <?php
    $strHide = "Direccion";
    $strInsertAfter = "RFC";
    $strInput = "i1431";
    include('address.php');
    ?>
    //### END - MODULO DE CATALOGO UNICO DE PAIS/ESTADO/MUNICIPIO/COLONIA/CODIGO POSTAL ###

    $("#frm").submit(function(e) {
        if($('#<?php echo $strInput; ?>').val()==''){
            alert('Capture los campos de Pais, Estado, Municipio, Colonia y/o Codigo Postal');
            e.preventDefault();
            return false;
        }
    });


</script>
<script type="text/javascript">
    var var_direccion = $("#i1431").val();
    $(document).ready(function() {
        if ($("#i1431").val() != ''){
            //alert("No vacío: " + var_direccion);
            buscar_chat = $.ajax({
                type: "POST",
                url: "../../modulos/netwarmonitor/get_direccion.php",
                async: true,
                data: {direccion:var_direccion}
            }).done(function(response){
                //console.log(response);
                var j_direccion = JSON.parse(response);
                $("#pais").html($("#pais").text() + " - (" + j_direccion[0].Pais + ")");
                $("#estado").html($("#estado").text() + " - (" + j_direccion[0].Estado + ")");
                $("#municipio").html($("#municipio").text() + " - (" + j_direccion[0].Municipio + ")");
                $("#colonia").html($("#colonia").text() + " - (" + j_direccion[0].Colonia + ")");
                $("#cp").html($("#cp").text() + " - (" + j_direccion[0].CP + ")");
            });
        }
    });        
</script>