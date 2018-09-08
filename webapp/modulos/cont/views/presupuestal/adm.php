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
    <h3>Clasificación Administrativa</h3>
    <div class="row">
        <div class="col-xs-12 col-md-2">Sector Público:</div>
        <div class="col-xs-12 col-md-10">
            <input type='hidden' value='' id='mod_id'><input type='hidden' value='' id='mod_clave'>
            <select id='1' name='pub' onchange="cargaTipo(this)" style='width:150px;'>
                <option value='0'>Seleccione una opción</option>
                <?php
                    while($pub = $sector_publico->fetch_assoc())
                    {
                        echo "<option value='".$pub['num']."'>".$pub['num']." / ".$pub['des']."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">Sector Financiero:</div>
        <div class="col-xs-12 col-md-10">
            <select id='2' name='fin' onchange='cargaTipo(this)' style='width:150px;'></select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">Sector Economia 1:</div>
        <div class="col-xs-12 col-md-10">
            <select id='3' name='eco1' onchange="cargaTipo(this)" style='width:150px;'></select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">Sector Economia 2:</div>
        <div class="col-xs-12 col-md-10">
            <select id='4' name='eco2' onchange="cargaTipo(this)" style='width:150px;'></select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">Entes Publicos(Opciones):</div>
        <div class="col-xs-12 col-md-10">
            <select id='5' name='ent' onchange="javascript:entes2()" style='width:150px;'></select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-2">
            Entes Públicos<br ><input type='text' id='ent2' name='ent2' onchange="javascript:creaClave()">
        </div>
        <div class="col-xs-12 col-md-10">
            Descripción<br ><input type='text' id='desc' name='desc'>
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
            <button type='submit' name='cancel' id='cancel' onclick="javascript:window.location = 'index.php?c=Pre_Adm&f=Index'">Cancelar</button>
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

        key = $("#1").val()+"."

        if($("#2").val() == null)
        {
            key += "0."
        }
        else
        {
            key += $("#2").val()+"."
        }

        if($("#3").val() == null)
        {
            key += "0."
        }
        else
        {
            key += $("#3").val()+"."
        }

        if($("#4").val() == null)
        {
            key += "0."
        }
        else
        {
            key += $("#4").val()+"."
        }

        if($("#ent2").val() == '')
        {
            key += "0"
        }
        else
        {
            key += $("#ent2").val()
        }
        

        $("#clave").val(key)
        $("#lab_clave").text(key)
    }
    function cargaTipo(tipo)
    {
        //alert(tipo.id)
        
        var v;
        var next = parseInt(tipo.id)+1

        if(parseInt($('#'+tipo.id).val()) > 0)
        {
            $.post("ajax.php?c=Pre_Adm&f=BuscaTipo",
            {
                pub:    $('#1').val(),
                fin:    $('#2').val(),
                eco1:   $('#3').val(),
                eco2:   $('#4').val(),
                idd: tipo.id
            },
            function(data)
            {
                for(i=next;i<=5;i++)
                    {
                        $("#"+i).html('')
                    }
                $("#"+next).html(data)
                v = $("#mod_clave").val().split('.')
                $("#"+next).val(v[parseInt(next)-1]).change()
                if(parseInt(next)==5)
                {
                    $("#ent2").val(v[4])
                }
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

         $.post("ajax.php?c=Pre_Adm&f=Guardar",
            {
                clave   :  $('#clave').val(),
                desc    :  $('#desc').val(),
                idd     :  id
            },
            function(callback)
            {
                if(parseFloat(callback))
                {
                    window.location = "index.php?c=Pre_Adm&f=Index";
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
        /*for(var id=1;id<=4;id++)
        {
            alert(cc[id-1])
            $('#'+id).val(cc[id]).change()    
        }*/

        $('#1').val(cc[0]).change()
        $('#ent2').val(cc[4])
        $('#desc').val(d)
        $('#cancel').show()

    }

    function eliminar(id)
    {
        $("#id-"+id).css("background-color","#c0d459")
        if(confirm("Esta seguro que desea eliminar esta Clave?"))
        {
            $.post("ajax.php?c=Pre_Adm&f=Eliminar",
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

    function entes2()
    {
        $("#ent2").val($("#5").val())
        creaClave()
    }
</script>