<style>
.row
{
    margin-bottom:20px;
}
.container
{
    margin-top:20px;
}
</style>
<?php
require "views/partial/modal-generico.php";
?>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-4"><h3>Selecciona el Ejercicio Inicial</h3></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2 col-md-offset-4">
            <select id='inicial' name='inicial' class="form-control">
                <?php
                    echo $select;
                ?>
            </select></div>
        <div class="col-xs-12 col-md-6">
            <button class="btn btn-default" onclick='generar()'>Generar <span class='glyphicon glyphicon-ok'></span></button>
        </div>
    </div>
    <?php
    $mensaje_loading = "Generando Ejercicios Iniciales.";
    require "views/partial/loading_all.php";
    ?>
</div>
<script type="text/javascript">
$(function()
 {
$("#loading").hide()
$('#modal-generico-boton-uno').on('click',function()
{
    $(this).attr('disabled',true);
        $('#modal-generico-container').modal('hide');
        $("#loading").show()
        $.post('ajax.php?c=configuracion&f=guardaInicial', 
        {
            inicial: $("#inicial").val()
        }, 
        function(data) 
        {
            if(parseInt(data))
            {
                //alert(data)
                window.location = 'index.php?c=configuracion&f=general';
            }
        });
});
$('#modal-generico-boton-dos').on('click',function()
{
    $('#modal-generico-container').modal('hide');
});
 });
    function generar()
    {
        $("#modal-generico-mensaje").text("Esta seguro que quiere iniciar en el ejercicio "+$("#inicial").val()+"?");
        $('#modal-generico-container').modal('show');
    }
</script>


