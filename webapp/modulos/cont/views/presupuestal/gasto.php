<link rel="stylesheet" href="js/select2/select2.css">
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
<div class="container well">
    <h3>Gastos</h3>
    <div class="row">
        <div class="col-xs-12 col-md-2">Finalidad:</div>
        <div class="col-xs-12 col-md-10">
            <input type='hidden' value='' id='mod_id'><input type='hidden' value='' id='mod_clave'>
            <select id='fin' name='fin' onchange='cargaFuncion()' style='width:150px;'>
                <option value='0'>Seleccione una opción</option>
                <?php
                    while($fin = $finalidades->fetch_assoc())
                    {
                        echo "<option value='".$fin['num']."'>".$fin['num']." / ".$fin['des']."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">Función:</div>
        <div class="col-xs-12 col-md-10">
            <select id='fun' name='fun' onchange='cargaSubFuncion()' style='width:150px;'></select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">SubFunción:</div>
        <div class="col-xs-12 col-md-10">
            <select id='sub' name='sub' onchange="javascript:creaClave()" style='width:150px;'></select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">
            SubSubFunción<br ><input type='text' id='subsub' name='subsub' onchange="javascript:creaClave()">
        </div>
        <div class="col-xs-12 col-md-10">
            Descripción<br ><input type='text' id='desc' name='desc' onchange="javascript:creaClave()">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">
            Clave Generada: 
        </div>
        <div class="col-xs-12 col-md-10">
            <input type="hidden" id='clave' name='clave'>
            <label id='lab_clave'></label>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <button type='submit' name='guardar' onclick='guardar()'>Guardar</button>
            <button type='submit' name='cancel' id='cancel' onclick="javascript:window.location = 'index.php?c=Pre_Gastos&f=Index'">Cancelar</button>
        </div>
    </div>
</div>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <b>Lista de Claves</b>
        </div>
    </div>
    <?php
        while( $c = $claves->fetch_assoc())
        {
            echo "<div class='row' id='id-".$c['Id']."'>
                        <div class='col-xs-12 col-md-2'>
                            ".$c['Clave']."
                        </div>
                        <div class='col-xs-12 col-md-6'>
                            ".$c['Descripcion']."
                        </div>
                        <div class='col-xs-6 col-md-2'>
                            <a href=\"javascript:modificar(".$c['Id'].",'".$c['Clave']."','".$c['Descripcion']."')\">Modificar</a>
                        </div>
                        <div class='col-xs-6 col-md-2'>
                        <a href=\"javascript:eliminar(".$c['Id'].")\">Eliminar</a>
                        </div>
                 </div>";
        }
    ?>
</div>

<script src="js/jquery-1.10.2.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/select2/select2.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script language='javascript'>
$(function()
 {
    $('#cancel').hide()
 });
    function creaClave()
    {
        var key;

        key = $("#fin").val()+"."

        if($("#fun").val() == null)
        {
            key += "0."
        }
        else
        {
            key += $("#fun").val()+"."
        }

        if($("#sub").val() == null)
        {
            key += "0."
        }
        else
        {
            key += $("#sub").val()+"."
        }

        if($("#subsub").val() == '')
        {
            key += "0"
        }
        else
        {
            key += $("#subsub").val()
        }
        

        $("#clave").val(key)
        $("#lab_clave").text(key)
    }
    function cargaFuncion()
    {
        var v;
        $("#fun").html("")
        $("#sub").html("")
        if(parseInt($('#fin').val()) > 0)
        {
            $.post("ajax.php?c=Pre_Gastos&f=BuscaFuncion",
            {
                fin:    $('#fin').val(),
                fun:  $('#fun').val()
            },
            function(data)
            {
                $("#fun").html(data)
                v = $("#mod_clave").val().split('.')
                $("#fun").val(v[1]).change()
                //alert(data)
            });
        }
        creaClave()

    }
    function cargaSubFuncion()
    {
        $("#sub").html("")
        if(parseInt($('#fun').val()) > 0)
        {
            $.post("ajax.php?c=Pre_Gastos&f=BuscaFuncion",
            {
                fin:    $('#fin').val(),
                fun:  $('#fun').val()
            },
            function(data)
            {
                $("#sub").html(data)
                v = $("#mod_clave").val().split('.')
                $("#sub").val(v[2]).change()
                //alert(data)
            });
        }
        creaClave()
    }
    function guardar()
    {
        var id;
        if($("#mod_id").val() == '')
        {
            id = '0'
        }
        else
        {
            id = $("#mod_id").val()
        }

         $.post("ajax.php?c=Pre_Gastos&f=Guardar",
            {
                clave   :  $('#clave').val(),
                desc    :  $('#desc').val(),
                idd     :  id
            },
            function(callback)
            {
                if(parseFloat(callback))
                {
                    window.location = "index.php?c=Pre_Gastos&f=Index";
                }
                //alert(data)
            });
    }
    function modificar(i,c,d)
    {
        //alert(c)
        $("body").animate({ scrollTop: 0 }, 500)
        $("#mod_clave").val(c)
        $("#mod_id").val(i)
        var cc = c.split('.')
        $('#fin').val(cc[0]).change()
        $('#subsub').val(cc[3])
        $('#desc').val(d)
        $('#cancel').show()

    }

    function eliminar(id)
    {
        $("#id-"+id).css("background-color","#c0d459")
        if(confirm("Esta seguro que desea eliminar esta Clave?"))
        {
            $.post("ajax.php?c=Pre_Gastos&f=Eliminar",
            {
                idd     :  id
            },
            function(callback)
            {
                if(parseFloat(callback))
                {
                    //window.location = "index.php?c=Pre_Gastos&f=Index";
                    $("#id-"+id).fadeOut(500);
                }
                //alert(data)
            });
        }
        else
        {
            $("#id-"+id).css("background-color","transparent")
        }

         
    }
</script>