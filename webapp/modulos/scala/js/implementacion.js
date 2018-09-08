    /// SERVICIOS
    function logoEmpresa(){        
        window.parent.agregatab("../catalog/gestor.php?idestructura=1&ticket=testing","Mi Organización","",2);        
    }
    function facturacionEmpresa(){        
        window.parent.agregatab("../catalog/gestor.php?idestructura=249&ticket=testing","Datos facturacion","",1284);
    }
    function sucursalEmpresa(){        
        window.parent.agregatab("../catalog/gestor.php?idestructura=86&ticket=testing","Sucursal","",2152);
    }
    function monedaEmpresa(){        
        window.parent.agregatab("../catalog/gestor.php?idestructura=256&ticket=testing","Tipo de moneda","",1674);
    }
    function bancoEmpresa(){        
        window.parent.agregatab("../catalog/gestor.php?idestructura=274&ticket=testing","Bancos","",1705);
    }
    /// COMERCIALIZADORA
    function preciosEmpresa(){
        window.parent.agregatab("../../modulos/appministra/index.php?c=configuracion&f=listas_precio","Listas de Precios","",1988);
    }
    function producosEmpresa(){        
        window.parent.agregatab("../../modulos/pos/index.php?c=producto&f=indexGridProductos","Productos","",2034);
    }

    /// CARGA INICIAL
    function cargaInicial(){
        var totalsp1 = 5;
        var progresp1 = 0;
        var newprogress = 0;
        $.ajax({
                    url: 'ajax.php?c=implementacion&f=cargarInicial',
                    type: 'post',
                    dataType: 'json',
            })
            .done(function(data) {
                $.each(data, function(index, val) {
                    // servicios
                    if(val.logo == 1){
                       $("#sp1logo").removeClass('btn-primary').addClass('btn-success').prop('onclick',null).off('click').html('<span class="glyphicon glyphicon glyphicon-check">'); 
                       progresp1 = progresp1 +1;
                    }
                    if(val.datosF == 1){
                       $("#sp1factura").removeClass('btn-primary').addClass('btn-success').prop('onclick',null).off('click').html('<span class="glyphicon glyphicon glyphicon-check">'); 
                       progresp1 = progresp1 +1;
                    }
                    if(val.sucursal == 1){
                       $("#sp1sucursal").removeClass('btn-primary').addClass('btn-success').prop('onclick',null).off('click').html('<span class="glyphicon glyphicon glyphicon-check">'); 
                       progresp1 = progresp1 +1;
                    }
                    if(val.moneda == 1){
                       $("#sp1moneda").removeClass('btn-primary').addClass('btn-success').prop('onclick',null).off('click').html('<span class="glyphicon glyphicon glyphicon-check">'); 
                       progresp1 = progresp1 +1;
                    }
                    if(val.banco == 1){
                       $("#sp1banco").removeClass('btn-primary').addClass('btn-success').prop('onclick',null).off('click').html('<span class="glyphicon glyphicon glyphicon-check">'); 
                       progresp1 = progresp1 +1;
                    }

                    newprogress = (progresp1 * 100) / totalsp1;
                    newprogress = newprogress+'%';

                    $('#sp1pro').attr('aria-valuenow', newprogress).css('width',newprogress);

                    /// comercial
                    if(val.listaP == 1){
                       $("#cp1listaP").removeClass('btn-primary').addClass('btn-success').prop('onclick',null).off('click').html('<span class="glyphicon glyphicon glyphicon-check">'); 
                       //progrecp2 = progrecp2 +1;
                    }
                    if(val.productos == 1){
                       $("#cp1prod").removeClass('btn-primary').addClass('btn-success').prop('onclick',null).off('click').html('<span class="glyphicon glyphicon glyphicon-check">'); 
                       //progrecp2 = progrecp2 +1;
                    }
                    
                    /*    
                    newprogresc = (progrecp2 * 100) / totalcp1;
                    newprogresc = newprogresc+'%';

                    $('#sp1pro').attr('aria-valuenow', newprogress).css('width',newprogress);
                    */

              
                });
                console.log(data);           
            })
    }