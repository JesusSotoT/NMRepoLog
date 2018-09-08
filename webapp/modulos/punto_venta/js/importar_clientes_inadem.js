
    function goBack()
    {
        window.location="../views/clientes/importar_clientes_inadem.php";
    }
    function registrarClientes(ruta){
        var contador_clientes = $("#contador_filas").val();
        var check = new Array();
        
        for(var i=2; i<=contador_clientes; i++)
        {
            if($("#chk_"+i).is(":checked"))
            {
                check.push(i);
            }
        }
        
        if(check.length < 1)
        {
            alert("No se seleccionó ningún cliente a importar");
        }
        else
        {
            if(check.length > 900){alert("Debe importar un maximo de 900 productos a la vez");}
            else{
                if (confirm('Los clientes del archivo excel se importarán a su sistema. ¿Está seguro?')){
                    $.ajax(
                    {
                        async: false,
                        url:'../funcionesBD/importar_clientes_inadem.php',
                        type: 'POST',
                        data: {funcion: "registraClientes", ruta: ruta, check: check},
                        success: function(callback)
                        {
                            if(callback == 1)
                            {
                                alert("Los clientes se importaron con éxito al sistema");
                                window.location="../views/clientes/importar_clientes_inadem.php";
                            }
                        }
                    });
                }
            }
        }
    }