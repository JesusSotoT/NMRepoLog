Array.prototype.last = function(){return (isNaN(this[this.length-1])) ? this[this.length-1] : parseFloat(this[this.length-1],10);};
$(document).ready(function(){
$('#blanca').show()
$('#abrir').click(function(){
        var link = $(this);
        var anchor  = link.attr('href');
        $('html, body').stop().animate({
            scrollTop: jQuery(anchor).offset().top
        }, 1000);
        return false;
});

$('#subir').click(function(){
        $('html, body').stop().animate({
            scrollTop: 1
        }, 1000);
        return false;
});

$("#tipo,#sucursal,#estado,#municipio,#encargado,#clasificador").select2()
nuevo()

    
    // INICIA DEFINICION DE DATOS, PLUGINS E INTERFACES
        var posX = 0;
        var posY = 0;
        var posY_interfaz = 0;
        var array = [];
        clear();
        var cpy = [];


       //EXTENDIENDO LA FUNCION CONTAINS PARA HACERLO CASE-INSENSITIVE
  $.extend($.expr[":"], {
"containsIN": function(elem, i, match, array) {
return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
}
});
//-------------------------------------------------------------

    // INICIA GENERACION DE BUSQUEDA
            $("#search").bind("keyup", function(evt){
                var display = $("#display").prop('checked') ? 1 : 0;
                //console.log($(this).val().trim());
                if(evt.type == 'keyup')
                {
                    if(!parseInt(display))
                    {
                        $("ul li[activo='1']:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
                        $("ul li[activo='1']:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
                        $("ul li[activo='1']:containsIN('*1_-{}*')").css('display','table-row');
                        if($(this).val().trim() === '')
                        {
                            $("ul li[activo='1']").css('display','table-row');
                        }
                    }
                    else
                    {
                        $("ul li:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
                        $("ul li:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
                        $("ul li:containsIN('*1_-{}*')").css('display','table-row');
                        if($(this).val().trim() === '')
                        {
                            $("ul li ").css('display','table-row');
                        }
                    }
                }

            });
        // TERMINA GENERACION DE BUSQUEDA
        
        // INICIAN  EVENTOS DE LISTA (Modificacion de Cuentas y SlideToggle de cuentas, click derecho)
        $('body').delegate("img",'click',function(){
            $(this).siblings("span").click();
        });

        $('body').delegate("li>span",'click dblclick contextmenu',function(event){
            switch(event.type)
            {
                case 'click':
                    if( $(".movable").length === 0 )
                    {
                        if( $(this).parent("li").children("ul").children("li").length > 0 )
                        {
                            $(this).parent("li").children("ul").slideToggle();
                        }
                    }
                    else
                    {
                        $('.selected').removeClass("selected");
                        $(this).addClass("selected");
                        $("#context2").show().css({
                            top:posY,
                            left:posX
                        });
                    }
                    
                    break;
                case 'dblclick':
                    modificar($(this).attr('code'))
                    break;
            }
        });
    // TERMINAN EVENTOS DE LISTA
    cargaCuentas()


$('#blanca').hide()
});

//  clear() - Sirve para limpiar la Interfaz - 
function clear()
{
    $("input:text").val('');
    $("#group_dig").val(0);
    $("#form,.layer").fadeOut();
    if($('.selected').length !== 0)
    {
        $(".selected").removeClass('selected');
    }
    $('#sve').removeAttr("disabled");
}



// sendObject() - Ejecuta transacciones
function sendObject(object, type)
{
    var ajaxSend = null;
    var sending = true;
    var destination = "";
    if(Object.prototype.toString.call(object) == "[object Object]" || Object.prototype.toString.call(object) == "[object Array]" || (object == 'init'))// Validando si es un objeto...
    {
        destination = "ajax.php?c=almacenes&f=" + type;
    }
    else
    {
        sending = false;
        alert('sendObject: El elemento no es un Objeto.');
    }

    if (sending)
    {
        ajaxSend = $.ajax({
            type     : 'post',
            url      : destination,
            // dataType : 'text',
            async : false,
            data     : {data:object},
            beforeSend: function(){
            }
        });

        ajaxSend.done(function(data){
            return data;
        });
        
        //console.log(" [ " + type + "]" + ajaxSend.responseText);
        ajaxSend.fail(function(){
            alert("El envio de datos ha fallado.");
        });
        return ajaxSend.responseText;
    }
    else{
        return ajaxSend;
    }
}


// setIcons - Genera la asignacion de iconos segun su nivel.
function setIcons()
{
    $('img').each(function(){
        var parent = $(this).parent('li').attr("data_type");
        switch(parent)
        {
            case '5':
                $(this).attr("src",'../../modulos/cont/images/1.gif');
                break;
            case '4':
                $(this).attr("src",'../../modulos/cont/images/4.gif');
                break;
            case '3':
                $(this).attr("src",'../../modulos/cont/images/2.gif');
                break;
             case '2':
                $(this).attr("src",'../../modulos/cont/images/3.gif');
                break;   
            case '1':
                $(this).attr("src",'../../modulos/cont/images/1.gif');
                break;
        }
    });
}

function cargaCuentas()
        {
            //INICIA OBTENCION DE ARBOL CONTABLE
            $('#cont>ul').html('')
            var data = sendObject('init','listaAlmacenes');
            jsonTree = $.parseJSON(data);
            var liArray = null;
            
            
                for ( var i = 0 ; i < jsonTree.length ; i++ )
                {
                    var inactivo = display = "";
                    if(!parseInt(jsonTree[i].activo))
                    {
                        inactivo = "style='color:gray;text-decoration:line-through;'";
                        display = "style='display:none;'";
                    }
                    
                    var contentLi = "<li "+display+" activo='"+jsonTree[i].activo+"' data_type = '"+jsonTree[i].id_almacen_tipo+"' data-father ='" + jsonTree[i].id_padre + "' data-id-acc='" + jsonTree[i].id + "' data-manual='"+ jsonTree[i].codigo_manual +"'>";
                    contentLi += "<img /><span title='" + jsonTree[i].id + "' code='" + jsonTree[i].id + "' "+inactivo+">( " + jsonTree[i].codigo_manual + " ) " + jsonTree[i].nombre + "</span><ul></ul></li>";

                    $('#cont>ul').append(contentLi);
                    $("li[data-id-acc='" + jsonTree[i].id + "'] > span").data(jsonTree[i]);
                }   
            
            
            

            $("#cont li").each(function()
            {
                var thiss = $(this);
                if($(this).children("span").data().removed == 1 ){
                    $(this).css('display','none');
                }
                var father = $(this).attr("data-father");
                if( father > 0 )
                {
                    $("li[data-id-acc='"+father+"' ]").children("ul").append(thiss);
                }
                var numClass = $(this).parents('ul').length - 1;
                $(this).addClass("x"+numClass);
            });
            
            setIcons();

            //$("li").tsort('span',{data:'father'},{data:'manual'});
            
            var toggleSorting = false;
            
            $(".sort").click(function(){
                if(!toggleSorting)
                {
                    $(this).text("Ordenar por ID de cuenta");
                    $("li").tsort();
                    toggleSorting = true;
                }
                else
                {
                    $(this).text('Ordenar Alfabeticamente');
                    $("li").tsort('span',{data:'father'},{data:'id-acc'});
                    $("li").tsort('span',{data:'id-acc'},{data:'father'});    
                    toggleSorting = false;
                }
            });
            //TERMINA OBTENCION DE ARBOL CONTABLE
        }

        //OTRAS COSAS

function nuevo()
{
    $("#titulo_captura h4").text("Nuevo Almacén")
    $("#idalmacen").val('0')
    $("#clave").val('')
    $("#nombre").val('')
    $("#tipo").val(1).trigger("change").prop("disabled",false);
    $("#depende").val(0);
    $("#depende").prop("disabled", true)
    $("#sucursal").val(1).trigger("change").prop("disabled",false);
    $("#estado").val(0).trigger("change").prop("disabled",false);
    $("#municipio").val(0).trigger("change").prop("disabled",false);
    $("#muni").val(0);
    $("#direccion").val('').prop("disabled",false);
    $("#encargado").val(0).trigger("change");
    $("#telefono").val('')
    $("#ext").val('')
    $('#consignacion').prop('checked', false)
    $("#clasificador").val(0).trigger("change");
    $("#status").val(1).trigger("change");

}        
function tipo()
{
    if(parseInt($("#tipo").val()) == 1)
        $("#consignacion").removeAttr("disabled")
    else
    {
        if($('#consignacion').prop('checked', true))
            $('#consignacion').prop('checked', false)
        $("#consignacion").attr("disabled","true")
    }


    if(parseInt($("#tipo").val()) == 1 || parseInt($("#tipo").val()) == 5)
    {
        $("#depende").val(0)
        $("#depende").prop("disabled",true)
        $("#sucursal").prop("disabled",false);
        $("#estado").prop("disabled",false);
        $("#municipio").prop("disabled",false);
        $("#direccion").prop("disabled",false);
    }

    if(parseInt($("#tipo").val()) != 1 && parseInt($("#tipo").val()) != 5)
    {
        $("#depende").removeAttr("disabled")
    }

        for(i=1;i<=5;i++)
            $("#depende option[tipo='"+i+"']").removeAttr('disabled')


        for(i=parseInt($("#tipo").val());i<=5;i++)
        {
            $("#depende option[tipo='"+i+"']").attr('disabled',true)
        }


}

function depende()
{
    if(parseInt($("#depende").val()))
    {
        $.post('ajax.php?c=almacenes&f=infopadre', 
        {
            id: $("#depende").val()
        }, 
        function(data) 
        {
            //alert(data)
            var datos = data.split("Ω");
            $("#muni").val(datos[2])
            $("#sucursal").val(datos[0]).trigger("change").prop("disabled",true);
            $("#estado").val(datos[1]).trigger("change").prop("disabled",true);
            $("#municipio").val(datos[2]).trigger("change").prop("disabled",true);
            $("#direccion").val(datos[3]).prop("disabled",true);
            $("#encargado").val(datos[4]).trigger("change")
            $("#telefono").val(datos[5])
            $("#ext").val(datos[6])
            $("#clasificador").val(datos[7]).trigger("change")
            
            if(parseInt(datos[8]))
                $("#consignacion").prop("checked",true)
            else
                $("#consignacion").prop("checked",false)
        });
    }
    else
    {
        $("#sucursal").prop("disabled",false);
        $("#estado").prop("disabled",false);
        $("#municipio").prop("disabled",false);
        $("#direccion").prop("disabled",false);   
        $("#consignacion").prop("checked",false)
    }
}

function estado()
{
    if(parseInt($("#estado").val()))
    {
        $.post('ajax.php?c=almacenes&f=getmunicipios', 
            {
                id: $("#estado").val()
            }, 
            function(data) 
            {
                //alert(data)
                $("#municipio").html("<option value='0'>Ninguno</option>");
                $("#municipio").append(data);
                $("#municipio").val($("#muni").val()).trigger("change");
            });
    }
}

function guardar()
{
    //Validaciones
    var validaciones = 0;
    var mensaje = "";
    if($("#clave").val() != "")
        validaciones++;
    else
        mensaje += " Falta llenar el campo Clave"

    if($("#nombre").val() != "")
        validaciones++;
    else
        mensaje += "\n Falta llenar el campo Nombre"

    if(parseInt($("#tipo").val()) == 1 || parseInt($("#tipo").val()) == 5)
        validaciones++;    

    if(parseInt($("#tipo").val()) != 1 && parseInt($("#depende").val()) != 0 && parseInt($("#depende").val()) != 5)
        validaciones++;
    else
        if(parseInt($("#tipo").val()) != 1 && parseInt($("#tipo").val()) != 5)
            mensaje += "\n Falta seleccionar una opcion en 'Depende de'";

    if(validaciones == 3)
        {
            $.post('ajax.php?c=almacenes&f=guardar', 
                {
                    id: $("#idalmacen").val(),
                    clave:$("#clave").val(),
                    nombre:$("#nombre").val(),
                    tipo:$("#tipo").val(),
                    depende:$("#depende").val(),
                    sucursal:$("#sucursal").val(),
                    estado:$("#estado").val(),
                    municipio:$("#municipio").val(),
                    direccion:$("#direccion").val(),
                    encargado:$("#encargado").val(),
                    telefono:$("#telefono").val(),
                    ext:$("#ext").val(),
                    consignacion:$("#consignacion").prop('checked') ? 1 : 0,
                    clasificador:$("#clasificador").val(),
                    status:$("#status").val()
                }, 
                function(data) 
                {
                    //alert(data)
                    if(parseInt(data))
                    {
                        alert("Registro Guardado correctamente");
                        location.reload();
                    }
                    else
                        alert("Hubo un error y no se pudo guardar.")
                });
        }
    else
        alert(mensaje)

}

function modificar(id)
{
    $("#titulo_captura h4").text("Actualizar Almacén ("+id+")")
    id
    $.post('ajax.php?c=almacenes&f=getdatos', 
        {
            id: id
        }, 
        function(data) 
        {
            var datos = data.split("Ω");
            $("#idalmacen").val(id)
            $("#clave").val(datos[0])
            $("#nombre").val(datos[1])
            $("#tipo").val(datos[2]).trigger("change").prop("disabled",true);
            $("#depende").val(datos[3]).prop("disabled",true);
            
            $("#sucursal").val(datos[4]).trigger("change");
            $("#muni").val(datos[6]);
            $("#estado").val(datos[5]).trigger("change");
            $("#direccion").val(datos[7]);
            
            if(parseInt(datos[2]) > 1 && parseInt(datos[2]) < 5)
            {
                $("#sucursal").prop("disabled",true);
                $("#estado").prop("disabled",true);
                $("#municipio").prop("disabled",true)
                $("#direccion").prop("disabled",true);
            }
            else
            {
                $("#sucursal").prop("disabled",false);
                $("#estado").prop("disabled",false);
                $("#municipio").prop("disabled",false)
                $("#direccion").prop("disabled",false);   
            }
            

            $("#encargado").val(datos[8]).trigger("change");
            $("#telefono").val(datos[9])
            $("#ext").val(datos[10])
            if(parseInt(datos[11]))
                $("#consignacion").prop("checked",true)
            else
                $("#consignacion").prop("checked",false)
            $("#consignacion").prop("disabled",true)
            $("#clasificador").val(datos[12]).trigger("change");
            $("#status").val(datos[13]).trigger("change");
        });
}

function cancelar()
{
    nuevo()
}

function inactivas()
{
    var display = $("#display").prop('checked') ? 1 : 0;
    if(parseInt(display))
        $("li[activo='0']").css("display","block")
    else
        $("li[activo='0']").css("display","none")
}

        
