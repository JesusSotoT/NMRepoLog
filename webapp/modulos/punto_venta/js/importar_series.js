
    function goBack()
    {
        window.location="../views/series/importar_series.php";
    }
    function registrarSeries(ruta){
        
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
            alert("No se seleccionó ningúna series a importar");
        }
        else
        {
            if(check.length > 900){alert("Debe importar un maximo de 900 productos a la vez");}
            else{
                if (confirm('Las series del archivo excel se importarán a su sistema. ¿Está seguro?')){
                    $.ajax(
                    {
                        async: false,
                        url:'../funcionesBD/importar_series.php',
                        type: 'POST',
                        data: {funcion: "registraSeries", ruta: ruta, check: check},
                        success: function(callback)
                        {
                            if(callback == 1)
                            {
                                alert("Las series se importaron con éxito al sistema");
                                window.location="../views/series/importar_series.php";
                            }
                        }
                    });
                }
            }
        }
    }