
    function imgprov(){            
        window.parent.agregatab("../../modulos/punto_venta/catalogos/proveedor.php","Beneficiarios/Proveedores ","",2144)
    }
    function imgcomp(){                   
        window.parent.agregatab("../../modulos/appministra/index.php?c=compras&f=ordenes","Ordenes de compra","",1980)
    }
    function imginvent(){  
        window.parent.agregatab("../../modulos/appministra/index.php?c=reportes&f=inventarioactual","Inventario Actual","",2124)                     
    }
    function imgventas(){ 
    window.parent.agregatab("../../modulos/pos/index.php?c=caja&f=ventasGrid","Ventas","",2106)                      
    }
    function imgclient(){   
    window.parent.agregatab("../../modulos/pos/index.php?c=cliente&f=indexGrid","Clientes AppH","",2049)                    
    }

    function cuentasPagar(){
            $.ajax({
                    url: 'ajax.php?c=dash&f=ant_saldos_reporte',
                    type: 'post',
                    dataType: 'json',
                    data:{idPrvs: '0',f_cor: ''}
            })
            .done(function(data) {
                $.each(data, function(index, val) {
                    $("#saldoFP").text(val.saldoFinal);
                    $("#saldoSinV").text(val.saldoSinV);
                    $("#saldo1_15").text(val.saldo1_15);
                    $("#saldo16_30").text(val.saldo16_30);
                    $("#saldo31_45").text(val.saldo31_45);
                    $("#saldo45mas").text(val.saldo45mas);
                });             
            })
    }
    function repCuentasPagar(){
        window.parent.agregatab("../../modulos/appministra/index.php?c=Reportes_Cuentas&f=ant_saldos","Antigüedad de Saldos Proveedores","",208 ); 
    }
    function cuentasCobrar(){
            $.ajax({
                    url: 'ajax.php?c=dash&f=ant_saldos_reporte_cxc',
                    type: 'post',
                    dataType: 'json',
                    data:{ids: '0',f_cor: ''}
            })
            .done(function(data) {
                $.each(data, function(index, val) {
                    $("#saldoFC").text(val.saldoFinal);
                    $("#saldoSinVC").text(val.saldoSinV);
                    $("#saldo1_15C").text(val.saldo1_15);
                    $("#saldo16_30C").text(val.saldo16_30);
                    $("#saldo31_45C").text(val.saldo31_45);
                    $("#saldo45masC").text(val.saldo45mas);
                });             
            })
    }
    function repCuentasCobrar(){
        window.parent.agregatab("../../modulos/appministra/index.php?c=Reportes_Cuentas&f=ant_saldos_cxc","Antigüedad de Saldos Clientes","",2100);
    }


    /// Grafica Ventas 

    function ventas(){
        var data = [
        { year: '2008', value: 3 },
        { year: '2009', value: 0 },
        { year: '2010', value: 5 },
        { year: '2011', value: 0 },
        { year: 'sdf', value: 1 },
        { year: '2013', value: 5 },
        { year: '2014', value: 0 },
        { year: '2015', value: 1 }
      ]; 

      graficaVentas(data);
    }
    

      

    function graficaVentas(data){
        new Morris.Line({
      // id del contenedor
      element: 'grafVentas',
      // los datos son leidos de un array
      data:data,
      // El nombre del atributo de registro de datos que contiene valores x.
      xkey: 'year',
      parseTime: false, /// elimina el requisito de fecha en valores de x
      // Una lista de nombres de atributos de registro de datos que contienen valores y.
      ykeys: ['value'],
      // Las etiquetas para las ykeys - se mostrarán cuando
      //labels: ['Value']
    });
    }