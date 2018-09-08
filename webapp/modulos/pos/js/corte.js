
function verCorte(){
    //caja.mensaje('Procesando...');
    var idCorte = $('#idCorte').val();
    var desde = $('#desdeCut').val();
    var hasta = $('#hastaCut').val();
    var user = $('#empleado').val();
    
    $.ajax({
        url: 'ajax.php?c=caja&f=obtenCorte',
        type: 'post',
        dataType: 'json',
        data: {show: 1,
                desde : desde,
                hasta : hasta,
                user : user
            },
    })
    .done(function(resCor) {
        console.log(resCor);
        //$('#desdeCut').val(resCor.desde);
        //$('#hastaCut').val(resCor.hasta);
        ///Llena la tabla de los pagos
        var cliente = '';
        $('.cutRows').empty();

         var table1 = $('#gridPagosCut').DataTable({
                            columnDefs: [
                                { "width": "20%", "targets": 0 }
                              ],
                            language: {
                            
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });



        table1.clear().draw();
        var x1 ='';
        var x1x ='';
        var Efectivo =0; 
        var TCredito =0;
        var TDebito =0;
        var CxC =0;
        var Cheque  =0;
        var Trans =0; 
        var SPEI  =0;
        var TRegalo  =0;
        var Ni  =0;
        var cambio =0;
        var Impuestos =0;
        var Monto =0;
        var Importe =0;
        var efectivoCambio2 =0;
        var desss = 0;
        var TVales = 0;
        var Otros = 0;
        var Cortesia = 0;

        $.each(resCor.ventas, function(index, val) {
            if(val.nombre==null){
                cliente = 'Publico General';
            }else{
                cliente = val.nombre;           
            }
            efectivoCambio = (val.Efectivo - val.cambio);
                    x1 = '<tr class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+cliente+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>$'+val.Efectivo+'</td>'+
                                    '<td>$'+val.TCredito+'</td>'+
                                    '<td>$'+val.TDebito+'</td>'+
                                    '<td>$'+val.CxC+'</td>'+
                                    '<td>$'+val.Cheque+'</td>'+
                                    '<td>$'+val.Trans+'</td>'+
                                    '<td>$'+val.SPEI+'</td>'+
                                    '<td>$'+val.TRegalo+'</td>'+
                                    '<td>$'+val.Ni+'</td>'+
                                    '<td>$'+val.TVales+'</td>'+
                                    '<td>$'+val.Cortesia+'</td>'+
                                    '<td>$'+val.Otros+'</td>'+
                                    '<td>$'+val.cambio+'</td>'+
                                    '<td>$'+val.Impuestos+'</td>'+
                                    '<td>$'+val.Monto+'</td>'+
                                    '<td>$'+val.descuentoGeneral+'</td>'+
                                    '<td>$'+val.Importe+'</td>'+
                                    '<td>$'+efectivoCambio.toFixed(2)+'</td>'+
                                    '</tr>';
                    table1.row.add($(x1)).draw();

                    Efectivo +=  parseFloat(val.Efectivo);
                    TCredito += parseFloat(val.TCredito);
                    TDebito += parseFloat(val.TDebito);
                    CxC += parseFloat(val.CxC);
                    Cheque += parseFloat(val.Cheque);
                    Trans += parseFloat(val.Trans);
                    SPEI += parseFloat(val.SPEI);
                    TRegalo +=parseFloat(val.TRegalo);
                    Ni += parseFloat(val.Ni);
                    cambio += parseFloat(val.cambio);
                    Impuestos += parseFloat(val.Impuestos);
                    Monto += parseFloat(val.Monto);
                    Importe += parseFloat(val.Importe);
                    efectivoCambio2 += parseFloat(efectivoCambio); 
                    desss +=parseFloat(val.descuentoGeneral); 
                    TVales += parseFloat(val.TVales);
                    Otros += parseFloat(val.Otros);
                    Cortesia += parseFloat(val.Cortesia);

        }); 
                            x1x = '<tr style="background:#EDEDED;" class="cutRows"><td colspan="3"></td><td>T. EF</td><td>TC</td><td>TD</td><td>CR</td><td>CH</td><td>TRA</td><td>SPEI</td><td>TR</td><td>NI</td><td>TVales</td><td>Cortesía</td><td>Otros</td><td>Cambio</td><td>Impuestos</td><td>Monto</td><td>Des.</td><td>Importe</td><td>Ingresos</td></tr>';  
                            x1x += '<tr stuyle="backgroud:white;" class="cutRows">'+
                                    '<td colspan="3">Totales</td>'+
                                    
                                    '<td>$'+Efectivo.toFixed(2)+'</td>'+
                                    '<td>$'+TCredito.toFixed(2)+'</td>'+
                                    '<td>$'+TDebito.toFixed(2)+'</td>'+
                                    '<td>$'+CxC.toFixed(2)+'</td>'+
                                    '<td>$'+Cheque.toFixed(2)+'</td>'+
                                    '<td>$'+Trans.toFixed(2)+'</td>'+
                                    '<td>$'+SPEI.toFixed(2)+'</td>'+
                                    '<td>$'+TRegalo.toFixed(2)+'</td>'+
                                    '<td>$'+Ni.toFixed(2)+'</td>'+
                                    '<td>$'+TVales.toFixed(2)+'</td>'+
                                    '<td>$'+Cortesia.toFixed(2)+'</td>'+
                                    '<td>$'+Otros.toFixed(2)+'</td>'+
                                    '<td>$'+cambio.toFixed(2)+'</td>'+
                                    '<td>$'+Impuestos.toFixed(2)+'</td>'+
                                    '<td>$'+Monto.toFixed(2)+'</td>'+
                                    '<td>$'+desss.toFixed(2)+'</td>'+
                                    '<td style="background-color: #ffccdd;">$'+Importe.toFixed(2)+'</td>'+
                                    '<td style="background-color: #A9F5A9;">$'+efectivoCambio2.toFixed(2)+'</td>'+
                                    '</tr>';
                $('#gridPagosCutTotales tr:last').after(x1x);
    ///Lena la tabla de tarjetas        
        /*$.each(resCor.tarjetas, function(index, val) {
        $('#gridTarjetas tr:last').after('<tr class="cutRows">'+
                                    '<td>'+val.tarjeta+'</td>'+
                                    '<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                    '</tr>');     
        });*/


/**/
        var table10 = $('#gridTarjetas').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table10.clear().draw();
        var x10 ='';
        var x10x ='';
        var cantidad10 = 0;

        $.each(resCor.tarjetas, function(index, val) {
                    x10 = '<tr class="cutRows">'+
                                    '<td>'+val.tarjeta+'</td>'+
                                    '<td>'+parseFloat(val.total).toFixed(2)+'</td>'+
                                    '</tr>';
                    table10.row.add($(x10)).draw(); 

                    cantidad10 += parseFloat(parseFloat(val.total).toFixed(2));
        }); 

          x10x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad10.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridTarjetasCutTotales tr:last').after(x10x);


                 /**/


        ///Llena la tabla de los productos
        var table2 = $('#gridProductosCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });

        table2.clear().draw();
        var x2 ='';
        var x2x = '';
        var Cantidad = 0;
        var Descuento = 0;
        var Impuestos2 = 0;
        var Subtot = 0;
        $.each(resCor.productos, function(index, val) {
                    x2 = '<tr class="cutRows">'+
                                    '<td>'+val.codigo+'</td>'+
                                    '<td>'+val.nombre+'</td>'+
                                    '<td align="center">'+val.Cantidad+'</td>'+
                                    '<td>$'+val.preciounitario+'</td>'+
                                    '<td>$'+val.Descuento+'</td>'+
                                    '<td>$'+val.Impuestos+'</td>'+
                                    '<td>$'+val.Subtot+'</td>'+
                                    '</tr>';
                    table2.row.add($(x2)).draw();

                    Cantidad += parseFloat(val.Cantidad);
                    Descuento += parseFloat(val.Descuento);
                    Impuestos2 += parseFloat(val.Impuestos2);
                    Subtot += parseFloat(val.Subtot);

        });                 
                            x2x = '<tr style="background:#EDEDED;" class="cutRows"><td colspan="4">Totales</td><td>Total Descuento</td><td>Total Impuestos</td><td>Total </td></tr>';     
                            x2x += '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+  
                                                
                                    '<td>$'+Descuento.toFixed(2)+'</td>'+
                                    '<td>$'+Impuestos.toFixed(2)+'</td>'+
                                    '<td style="background-color: #ffccdd;">$'+Subtot.toFixed(2)+'</td>'+
                                    '</tr>';
            $('#gridProductosCutTotales tr:last').after(x2x);             
        ///Llena la Tabla de retiros
        var table3 = $('#gridRetirosCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table3.clear().draw();
        var x3 ='';
        var x3x ='';
        var cantidad3 = 0;
        $.each(resCor.retiros, function(index, val) {
                    x3 = '<tr idRetiro="'+val.id+'" class="cutRows">'+
                                    '<td>'+val.id+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>'+val.concepto+'</td>'+
                                    '<td>'+val.usuario+'</td>'+
                                    '<td>$'+val.cantidad+'</td>'+
                                    '</tr>';
                    table3.row.add($(x3)).draw(); 

                    cantidad3 += parseFloat(val.cantidad);
        }); 

          x3x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad3.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridRetirosCutTotales tr:last').after(x3x);




        ///Llena la Tabla de abonos
        var table4 = $('#gridAbonosCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table4.clear().draw();
        var x4 ='';
        var x4x ='';
        var cantidad4 = 0;
        var abonoEfectivo = 0;
        $.each(resCor.abonos, function(index, val) {
                    x4 = '<tr idRetiro="'+val.id+'" class="cutRows">'+
                                    '<td>'+val.id+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>'+val.concepto+'</td>'+
                                    '<td>'+val.usuario+'</td>'+
                                    '<td>$'+val.cantidad+'</td>'+
                                    '</tr>';
                    table4.row.add($(x4)).draw(); 
                    if(val.id_forma_pago == "1")
                        abonoEfectivo += parseFloat(val.cantidad);

                    cantidad4 += parseFloat(val.cantidad);
        }); 

          x4x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #A9F5A9;">$'+cantidad4.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridAbonosCutTotales tr:last').after(x4x);




        ///Llena la Tabla de propinas
        var table5 = $('#gridPropinasCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table5.clear().draw();
        var x5 ='';
        var x5x ='';
        var cantidad5 = 0;
        $.each(resCor.propinas, function(index, val) {
                    x5 = '<tr idRetiro="'+val.id_venta+'" class="cutRows">'+
                                    '<td>'+val.id_venta+'</td>'+
                                    '<td>'+val.nombre+'</td>'+
                                    '<td>'+val.fecha+'</td>'+
                                    '<td>'+val.efectivo+'</td>'+
                                    '<td>'+val.visa+'</td>'+
                                    '<td>'+val.mc+'</td>'+
                                    '<td>'+val.amex+'</td>'+
                                    '<td>'+val.total+'</td>'+
                                    '</tr>';
                    table5.row.add($(x5)).draw(); 

                    cantidad5 += parseFloat(val.total);
        }); 

          x5x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #A9F5A9;">$'+cantidad5.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridPropinasCutTotales tr:last').after(x5x);


        ///Llena la Tabla de devoluciones
        var table9 = $('#gridCortesiasCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table9.clear().draw();
        var x9 ='';
        var x9x ='';
        var cantidad9 = 0;
        $.each(resCor.ventas, function(index, val) {
            if(val.Cortesia > 0){
                    x9 = '<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.Cortesia+'</td>'+
                                    '</tr>';
                    table9.row.add($(x9)).draw(); 

                    cantidad9 += parseFloat(val.Cortesia);
                }
        }); 

          x9x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad9.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridCortesiasCutTotales tr:last').after(x9x);

        ///Llena la Tabla de devoluciones
        var table6 = $('#gridDevolucionesCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table6.clear().draw();
        var x6 ='';
        var x6x ='';
        var cantidad6 = 0;

        $.each(resCor.devoluciones, function(index, val) {
                    x6 = '<tr idRetiro="'+val.id_venta+'" class="cutRows">'+
                                    '<td>'+val.id_ov+'</td>'+
                                    '<td>'+val.total+'</td>'+
                                    '</tr>';
                    table6.row.add($(x6)).draw(); 

                    cantidad6 += parseFloat(val.total);
        }); 

          x6x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad6.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridDevolucionesCutTotales tr:last').after(x6x);


        ///Llena la Tabla de cancelaciones
        var table7 = $('#gridCancelacionesCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table7.clear().draw();
        var x7 ='';
        var x7x ='';
        var cantidad7 = 0;
        $.each(resCor.cancelaciones, function(index, val) {
                    x7 = '<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.monto+'</td>'+
                                    '</tr>';
                    table7.row.add($(x7)).draw(); 

                    cantidad7 += parseFloat(val.monto);
        }); 

          x7x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #ffccdd;">$'+cantidad7.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridCancelacionesCutTotales tr:last').after(x7x);



        ///Llena la Tabla de facturas
        var table8 = $('#gridFacturasCut').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
        table8.clear().draw();
        var x8 ='';
        var x8x ='';
        var cantidad8 = 0;
        $.each(resCor.facturas, function(index, val) {
                    x8 = '<tr idRetiro="'+val.idVenta+'" class="cutRows">'+
                                    '<td>'+val.idVenta+'</td>'+
                                    '<td>'+val.monto+'</td>'+
                                    '</tr>';
                    table8.row.add($(x8)).draw(); 

                    cantidad8 += parseFloat(val.monto);
        }); 

          x8x = '<tr style="background:white;" class="cutRows">'+
                                    '<td colspan="4">Totales</td>'+
           
                                    '<td style="background-color: #A9F5A9;">$'+cantidad8.toFixed(2)+'</td>'+
                                    '</tr>';
                 $('#gridFacturasCutTotales tr:last').after(x8x);

        //$('#saldo_inicial').val(resCor.montoInical);
        //$('#monto_ventas').val( $('#monto_ventas').val - (monto_ventas + Cortesia) );
        var saldoDisponibleMret = parseFloat($('#saldo_disponible').val()) - parseFloat(cantidad3.toFixed(2)) + parseFloat(cantidad4.toFixed(2));
        //alert(saldoDisponibleMret);
        //$('#saldo_disponible').val(saldoDisponibleMret.toFixed(2));
        
        //$('#saldo_disponible').val(resCor.monto_ventas.toFixed(2));
        $('#saldoRetirosCaja').val(cantidad3);

        var x  = parseFloat(saldoDisponibleMret) - parseFloat($('#retiro_caja').val());


        var saldoFinal = parseFloat(x) + parseFloat($('#deposito_caja').val());
        
        /* invertdos
        $('#saldo_final').val(saldoFinal.toFixed(2));
        $('#totalof').val(resCor.totalof.toFixed(2));
        */
$('#monto_ventas').val( parseFloat($('#monto_ventas').val())  );
        $('#totalof').val(saldoFinal);
        $('#saldo_final').val( parseFloat($('#saldo_inicial').val()) + parseFloat($('#monto_ventas').val()) + parseFloat(cantidad4) - parseFloat(cantidad3) );
        $('#saldo_disponible').val(parseFloat( $('#saldo_inicial').val()) + efectivoCambio2 + parseFloat(abonoEfectivo) - parseFloat(cantidad3) );

//$('#monto_ventas').val( parseFloat(Cortesia) );
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    

}
function regresar(){
    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=caja&f=cortesGrid';
}
function imprimeCorte(id){
    //alert(id);
    window.open("corteImpreso.php?corte="+id);


    
}
function imprimeCorteTicket(id){
    window.open("corteImpresoTicket.php?corte="+id);
}
function strTipoCorte(t){
    switch(t){
        case "1": return "N";
        case "2": return "P";
        case "3": return "Z";
        default: return "N";
    }
}
function buscar(){
    var empleado = $('#empleado').val();
    var desde = $('#desde').val();
    var hasta = $('#hasta').val();
    $.ajax({
        url: 'ajax.php?c=caja&f=cortesfiltrados',
        type: 'post',
        dataType: 'json',
        data: {empleado : empleado,
                desde : desde ,
                hasta : hasta
                },
    })
    .done(function(resp) {
        console.log(resp);
        $('.cutRows').empty();

         var table1 = $('#tableCuts').DataTable();
        table1.clear().draw();
        var x2 = '';
        $.each(resp.cortes, function(index, val) {
           
                        x2 = '<tr class="cutRows">'+
                                    '<td>'+val.idCortecaja+'</td>'+
                                    /*'<td>'+strTipoCorte( val.tipoCorte )+'</td>'+*/
                                    '<td>'+val.usuario+'</td>'+
                                    '<td>'+val.fechainicio+'</td>'+
                                    '<td>'+val.fechafin+'</td>'+
                                    '<td>$'+val.saldoinicialcaja+'</td>'+
                                    '<td>$'+val.montoventa+'</td>'+
                                    '<td>$'+val.retirocaja+'</td>'+
                                    '<td>$'+val.abonocaja+'</td>'+
                                    '<td>$'+val.saldofinalcaja+'</td>'+
                                    
                                    '<td><a class="btn btn-primary active" href="index.php?c=caja&f=verCorte&idCorte='+val.idCortecaja+'"><i class="fa fa-list-ul"></i> Ver</a></td>'+
                                    '<td> <a class="btn btn-primary active" onclick="imprimeCorteTicket('+val.idCortecaja+ ');"><i class="fa fa-print"></i></a></td>'+
                                    '</tr>';
                                    table1.row.add($(x2)).draw(); 
        });
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    


}

function verArqueo() {
    var idCorte = parseInt( $('#idCorte').text() );
    console.log(idCorte);
    $.ajax({
        type: "POST",                                            
        url: "ajax.php?c=caja&f=obtenerArqueoCaja", 
        data: { "idCorte" : idCorte }, 
        dataType : 'json',                                       
        timeout: 1500,                                          
        beforeSend: function(data) {                                 
        },
        complete: function(data) {                  
        },
        success: function(data) { 
             $('#peso1, #peso2, #peso5, #peso10, #peso20, #peso50, #peso100, #peso200, #peso500, #peso1000').each(function(index, el) {
                $(this).val( data['pesos'][$(this).attr('id')] );
            });
            $('#centavo5, #centavo10, #centavo20, #centavo50').each(function(index, el) {
                $(this).val( data['centavos'][$(this).attr('id')] );
            });
            $('#totalArqueo').val(data.total);
        },
        error: function() {                                     
            alert("Error al cargar datos de arqueo de caja");
        }
    }); 
}







