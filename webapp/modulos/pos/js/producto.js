
/////producto 
function newProduct(){

    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=producto&f=index';
    
}
function guardar(){
    /* Peso y dimensiones 5campos */
    if($('[name="pesoCheck"]').is(':checked')){
        var box=1
        var boxPeso = $('#boxPeso').val();
        var boxAlto = $('#boxAlto').val();
        var boxLargo = $('#boxLargo').val();
        var boxAncho = $('#boxAncho').val();
    }else{
        var box=0;
        var boxPeso = '';
        var boxAlto = '';
        var boxLargo = '';
        var boxAncho = '';
    }


    

    var idProducto = $('#id').val();
    var nombre = $('#nombre').val();
    var codigo = $('#codigo').val();
    var precio = $('#precio').val();
    var deslarga = $('#deslarga').val();
    var descorta = $('#descorta').val();
    var uniCompra = $('#uniCompra').val();
    var uniVenta = $('#uniVenta').val();
    var departamento = $('#departamento').val();
    var familia = $('#familia').val();
    var linea = $('#linea').val();
    var maximo = $('#maximo').val();
    var minimo = $('#minimo').val();
    var tipoProd = $('#tipoProd').val();
    var costeo = $('#costeoSelect').val();
    var comision = $('#comision').val();
    var moneda = $('#moneda').val();
    var tipoCom = $('#tipoCom').val();
    var costoServicio = $('#costoServicio').val();
    var imagen = $('#imagenDir').val();
    var resena = $('#resena').val();
    var link = $('#link').val();

    var iepsForm = $('#formulaIeps').val();

    var proveedores = '';
    var cartrt = '';
    var listPreciosStr = '';
    var preciosSucursal = [];
    var listaImpuestos = '';
    var edicion = $('#editDescrio').val();

    if( $('input[name=configComision]:checked').val() != 3 ){
        if ( $('input[name=configComision]:checked').val() == 1 ) {
            var configComision = $('input[name=configComision]:checked').val();
            var porcentajeBaseComision = $($('.porcentajeBaseComision')[0]).val();
            var tipoComision = $($('.tipoComision')[0]).val();
        }
        else {
            var configComision = $('input[name=configComision]:checked').val();
            var porcentajeBaseComision = $($('.porcentajeBaseComision')[1]).val();
            var precioBaseComision =  $('.precioBaseComision').val() ;
            var tipoComision = $($('.tipoComision')[1]).val();
        }
        
    }
    else {
        var configComision = $('input[name=configComision]:checked').val();
    }

    var contador = 0;
    var contador2 = 0;
    var contador3 = 0;
    var contador4 = 0;
    
    if(nombre==''){
        alert('Debes de agregar un nombre al producto');
        return false;
    }
    if(codigo == ''){
        alert('Debes agregar un codigo al producto');
        return false;
    }
    if(precio  == ''){
        alert('Debes agregar un precio al producto');
        return false;
    }
    
    if(precio  < 0){
        alert('El precio del producto no debe ser negativo.');
        return false;
    }


       if($('#checkLotes').is(':checked')){
            lotes = 1;
       }else{
            lotes = 0;
       }
       if($('#checkSeries').is(':checked')){
            series = 1;
       }else{
            series = 0;
       }
       if($('#checkPedimentos').is(':checked')){
            pedimentos = 1;
       }else{
            pedimentos = 0;
       }



        if($('#consigna').is(':checked')){
            consigna = 1;
       }else{
            consigna = 0;
       }

       //alert(consigna);
        /*var hayImpuestosVacios = false;
    var contador_impuestos = $("#contador_impuestos").val();
    var impuestos_ids = new Array();
    var impuestos_valores = new Array();
    
    for(var i=0; i<contador_impuestos; i++)
    {
        if ($('#chk_'+i).is(':checked'))
        {
            impuestos_ids.push($('#chk_'+i).val());
            impuestos_valores.push($('#impuesto_'+i).val());
            
            if(impuestos_valores[i] == "")
            {
                hayImpuestosVacios = true;
            }
        }
    } */
    
   /* console.log(impuestos_ids);
    console.log(impuestos_valores);
    return;*/

    $("#provesList tr").each(function (index) 
    {   
        contador++;
        idProve = $(this).attr('idproved');
        precioPro = $('#pro_'+idProve).val();
        if(precioPro!=''){
            proveedores +='/'+idProve+'-'+precioPro;
        }
    });

    $("#caracList tr").each(function (index) 
    {   
        contador2++;
        crt = $(this).attr('idCarT');
        cartrt +='-'+crt;
    });

    $("#preciosList tr").each(function (index) 
    {   
        contador3++;
        price = $(this).attr('idlistpre');
        suc = $(this).attr('idsuc');

        precioTmp = $('input#id_'+price+'_'+suc).val();
        listPreciosStr +='-'+price+'*'+precioTmp+'|'+suc;
    });

    $("#preciosSucursal tr").each(function (index) 
    {   
        if(index != 0) {
            objTmp = {};
            objTmp.sucursal = $(this).attr('idsuc');
            objTmp.precio = $(this).find('input').val();
            preciosSucursal.push(objTmp);
        }
    });

    $("#impuestosList tr").each(function (index) 
    {   
        contador4++;
        impuesto = $(this).attr('idlistimpues');
        formula = $('#form_'+impuesto).val();
        listaImpuestos +='-'+impuesto+'/'+formula;
    });
    $('#btnSave').hide();
    $('#loadingPro').show();
    //alert(listaImpuestos);
    //return;
    var divisionSat = $('#divisionSat').val();
    var grupoSat = $('#grupoSat').val();
    var claseSat = $('#claseSat').val();
    var claveSat = $('#claveSat').val();

    //alert(`${divisionSat}  ${grupoSat}  ${claseSat}  ${claveSat} `);
    if( claveSat == "0" ) {
        if( claseSat != "0" ) 
            claveSat = claseSat;
        else {
            divisionSat = "0";
            grupoSat = "0";
            claseSat = "0";
            claveSat = "52839";
        }
    }
    //alert(`${divisionSat}  ${grupoSat}  ${claseSat}  ${claveSat} `);

//alert(claseSat);
    $.ajax({
        url: 'ajax.php?c=producto&f=guardaProducto',
        type: 'POST',
        dataType: 'json',
        data: {idProducto: idProducto,
                nombre : nombre,
                codigo : codigo,
                precio : precio,
                deslarga : deslarga,
                descorta : descorta,
                uniCompra : uniCompra,
                uniVenta : uniVenta,
                proveedores : proveedores,
                departamento : departamento,
                familia : familia,
                linea :linea,
                maximo : maximo,
                minimo : minimo,
                tipoProd : tipoProd,
                costeo : costeo,
                cartrt : cartrt,
                listPreciosStr : listPreciosStr,
                preciosSucursal : preciosSucursal,
                listaImpuestos : listaImpuestos,
                comision : comision,
                moneda : moneda,
                lotes : lotes,
                series : series,
                pedimentos : pedimentos,
                tipoCom : tipoCom,
                costoServicio : costoServicio,
                imagen : imagen,
                iepsForm : iepsForm,
                configComision : configComision,
                precioBaseComision : precioBaseComision,
                porcentajeBaseComision : porcentajeBaseComision,
                tipoComision : tipoComision,
                resena:resena,
                link:link,
                edicion : edicion,
                divisionSat : divisionSat,
                grupoSat : grupoSat,
                claseSat : claseSat,
                claveSat : claveSat,
                consigna : consigna,
                box : box,
                boxPeso : boxPeso,
                boxAlto : boxAlto,
                boxLargo : boxLargo,
                boxAncho : boxAncho,
                formulacion:{
                                cant_min: $('#cant_minima').val(),
                                factor:$('#factor').val(),
                                unidad: $('#unidad_compra_venta').val()
                            }
                //impuestos_ids : impuestos_ids,
                //impuestos_valores : impuestos_valores,
                },
    })
    .done(function(data) {
        console.log(data);
        if(data.status == true && data.idProducto !=''){
            $('#modalSuccess').modal({
                show:true,
            });
        }else{
            alert(data.mensaje);
            $('#btnSave').show();
            $('#loadingPro').hide();
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
} 
function redireccion(){
    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=producto&f=indexGridProductos';   
}
function guardarP(){
    var precioT = $("#precioMS").val();
    $("#modalCal").modal('hide');
    $("#precio").val(precioT);
}
function calcular(){
    var precio = $("#precioM").val()*1;
    if(precio == ''){
        alert('Debe ingresar un precio');
        return false;
    }
    var imp1 = imp2 = imp3 = imp4 = imp5 = imp6 = imp7 = imp8 = imp9 = imp10 = imp11 = imp12 = msj = 0;
    var val1 = val2 = val3 = val4 = val5 = val6 = val7 = val8 = val9 = val10 = val11 = val12 = 0;
    $("#impuestosList tr").each(function (index) 
    {   
        impuesto = $(this).attr('idlistimpues');
        valor = $(this).attr('vallistimpues');

        if(impuesto == 1){ imp1= 1; val1 = valor} // iva 16
        if(impuesto == 2){ imp2= 1; val2 = valor } // iva 0
        if(impuesto == 3){ imp3= 1; val3 = valor } // ivaE 0
        if(impuesto == 4){ imp4= 1; val4 = valor } // ivaRe 10.667
        if(impuesto == 5){ imp5= 1; val5 = valor } // ivaRe 4
        if(impuesto == 6){ imp6= 1; val6 = valor } // ISRRE 10
        if(impuesto == 7){ imp7= 1; val7 = valor } // ISH 2
        if(impuesto == 8){ imp8= 1; val8 = valor } // IEPS 8
        if(impuesto == 9){ imp9= 1; val9 = valor } // IEPS 160
        if(impuesto == 10){ imp10= 1; val10 = valor } // IEPS 30
        if(impuesto == 11){ imp11= 1; val11 = valor } // RTP 3
        if(impuesto == 12){ imp12= 1; val12 = valor } // iva 17
    });

    // SOLO IVA
    if(imp1 == 1 && imp2 == 0 && imp3 == 0 && imp4 == 0 && imp5 == 0 && imp6 == 0 && imp7 == 0 && imp8 == 0 && imp9 == 0 && imp10 == 0 && imp11 == 0 && imp12 == 0){        
        var iva = val1;
        iva = iva / 100;
        iva = iva + 1;
        precio = precio / iva;
        msj = 1;
        
    }
    // SOLO IEPS
    if(imp1 == 0 && imp2 == 0 && imp3 == 0 && imp4 == 0 && imp5 == 0 && imp6 == 0 && imp7 == 0 && imp8 == 1 && imp9 == 0 && imp10 == 0 && imp11 == 0 && imp12 == 0){
        var ieps = val8;
        ieps = ieps / 100;
        ieps = ieps + 1;        
        precio = precio / ieps;
        msj = 1;
    }

    // SOLO IVA RETENIDO
    if(imp1 == 0 && imp2 == 0 && imp3 == 0 && imp4 == 1 && imp5 == 0 && imp6 == 0 && imp7 == 0 && imp8 == 0 && imp9 == 0 && imp10 == 0 && imp11 == 0 && imp12 == 0){
        var iret = val4;
        iret = iret / 100;
        iret = iret + 1;        
        precio = precio * iret;
        msj = 1;
    }

    // SOLO IVA RETENIDO
    if(imp1 == 0 && imp2 == 0 && imp3 == 0 && imp4 == 0 && imp5 == 0 && imp6 == 1 && imp7 == 0 && imp8 == 0 && imp9 == 0 && imp10 == 0 && imp11 == 0 && imp12 == 0){
        var isrr = val6;
        isrr = isrr / 100;
        isrr = isrr + 1;        
        precio = precio * isrr;
        msj = 1;
    }
    /// combo IVA 16 . ISR RET 1.10. . IVA RET 1.10667
    if(imp1 == 1 && imp2 == 0 && imp3 == 0 && imp4 == 1 && imp5 == 0 && imp6 == 1 && imp7 == 0 && imp8 == 0 && imp9 == 0 && imp10 == 0 && imp11 == 0 && imp12 == 0){
        var iva = val1;
            iva = iva / 100;
            iva = iva + 1;
        var iret = val4;
            iret = iret / 100;
            iret = iret + 1; 
        var isrr = val6;
            isrr = isrr / 100;
            isrr = isrr + 1; 
        precio = (((precio/iva)*iret)*isrr);
        msj = 1;
    }

    if(msj == 0){
        alert('Calculo no Soportado!');
        precio = $("#precioM").val()*1;
    }

    $("#precioMS").val(precio);
}
function agregaListaImpues(){
    var idImp = $('#selectImpuestos').val();
    var formula = $('#formula').val();
    $('#formulaIeps').val(formula);
    var a = 1;
    $("#impuestosList tr").each(function (index) 
    {   
        
        impuesto = $(this).attr('idlistimpues');
        if(impuesto == idImp){
            alert('El impuesto ya se encuentra en la lista');
            a = 0;
        }
    }); 
    if(a==0){
        return false;
    }

    $.ajax({
        url: 'ajax.php?c=producto&f=getNewImpuesto',
        type: 'POST',
        dataType: 'json',
        data: {idImp: idImp},
    })
    .done(function(data) {
        console.log(data);
        if(formula==1){
            form = 'Primera Formula';
        }else if(formula==2){
            form = 'Segunda Formula';
        }else{
            form = '';
        }

        $('#impuestosList tr:last').after('<tr idListImpues="'+data[0].id+'" valListImpues="'+data[0].valor+'" id="imp_x_'+data[0].id+'"><td><span class="glyphicon glyphicon-remove-circle" onclick="removeImpues('+data[0].id+');"></span></td><td>'+data[0].nombre+'</td><td>%'+data[0].valor+'</td><td>'+form+'<input type="hidden" id="form_'+data[0].id+'" value="'+formula+'">'+'</td></tr>'); 
        $('#formula > option[value="0"]').attr('selected', 'selected');
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });    
}  
function borraProducto(idProducto){
    $('#eliminaProd').val(idProducto);
        $('#modalElimina').modal({
                show:true,
            });
}
function removeImpues(id){
    $('#imp_x_'+id).remove();
}
function borraProducto2(){
    var idProducto = $('#eliminaProd').val();
    $.ajax({
        url: 'ajax.php?c=producto&f=desactiva',
        type: 'post',
        dataType: 'json',
        data: {idProducto: idProducto},
    })
    .done(function(data) {
        
        console.log(data);
        if(data.estatus==true){
            alert('Se desactivó el producto');
            var pathname = window.location.pathname;
            window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=producto&f=indexGridProductos';
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function agregaProve(){
    var idPrv = $('#selectProveedor').val();  
    var precio = $('#provePrecio').val();
    var a = 1;
    $("#provesList tr").each(function (index) 
    {   
  
        idProve = $(this).attr('idproved');
  
        if(idProve==idPrv){
            alert('El proveedor ya se encuentra en la lista.');
           a = 0;
        }
    });
    
    if(a==0){
        return false;
    }
    $.ajax({
        url: 'ajax.php?c=producto&f=nombreProvedor',
        type: 'POST',
        dataType: 'json',
        data: {idPrv: idPrv},
    })
    .done(function(data) {
        console.log(data);
        $('#provesList tr:last').after('<tr idProved="'+data[0].idPrv+'" id="idPr_'+data[0].idPrv+'"><td><span class="glyphicon glyphicon-remove" onclick="removeProve('+data[0].idPrv+');"></span></span></td><td>'+data[0].razon_social+'</td><td>$'+precio+'<input type="hidden" value="'+precio+'" id="pro_'+data[0].idPrv+'"></td></tr>');
        $('#selectProveedor > option[value="0"]').attr('selected', 'selected');
        $('#provePrecio').val(''); 

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function agregaCarac(){
    var idCara = $('#selectCaract').val(); 
    var a = 1;
    $("#caracList tr").each(function (index) 
    {   
        crt = $(this).attr('idCarT');
        if(idCara == crt){
            alert('La caranteristica ya se encuentra en la lista');
            a = 0;
        }
    });
    if(a==0){
        return false;
    }
    $.ajax({
        url: 'ajax.php?c=producto&f=nombreCaracteristica',
        type: 'POST',
        dataType: 'json',
        data: {idCara: idCara},
    })
    .done(function(data) {
        console.log(data);
        $('#caracList tr:last').after('<tr idCarT="'+data[0].id+'" id="carac_'+data[0].id+'"><td><span class="glyphicon glyphicon-remove-circle" onclick="removeCarac('+data[0].id+');"></span></td><td>'+data[0].nombre+'</td></tr>');
        $('#selectCaract > option[value="0"]').attr('selected', 'selected');
        
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function agregaLista(){
    var sucAUX = $("#idsuc").val();
    var idsucursal = $('#selectsuc').val();
    var sucursal = $('#selectsuc option:selected').text();
    if(sucAUX > 0){
        idsucursal = $("#idsuc").val();
        sucursal = $("#nomsuc").val();
    }

    var idLista = $('#listaPrecio').val();
   
    
    var a=1;

    var precioBase = $('#precio').val();
    var suc = $("#preciosSucursal tr").filter( `[idSuc=${idsucursal}]` );
    if(suc.length != 0){
        precioBase = suc.find("input").val();
    }

    if(precioBase=='' || precioBase==0){
        alert('Para calcular el precio de la lista necesitas tener un precio base.');
        return false;
        
    } 

    if(idsucursal == 0){
        alert('Debe Seleccionar una sucursal');
        return false;
    }

    $("#preciosList tr").each(function (index) 
    {   
        price = $(this).attr('idlistpre');
        if(price == idLista){
            suc = $(this).attr('idsuc');
            if(suc == idsucursal){
                alert('La lista ya se encuentra agregada');
                a = 0;
                return false;
            }
        }
    });
    if(a==0){
        return false;
    } 



    $.ajax({
        url: 'ajax.php?c=producto&f=listaParametros',
        type: 'POST',
        dataType: 'json',
        data: {idLista: idLista},
    })
    .done(function(data) {
        console.log(data);
       
        var descuento =0;
        var precioFinal = 0;
        descuento = precioBase * data[0].porcentaje / 100;
         if(data[0].descuento == 1){
            precioFinal = parseFloat(precioBase) - parseFloat(descuento);
         }else{
            precioFinal = parseFloat(precioBase) + parseFloat(descuento);
         }

         if(data[0].tipo == "2"){
             $('#preciosList tr:last').after('<tr idListPre="'+data[0].id+'" idsuc="'+idsucursal+'" id="idLp_'+data[0].id+"_"+idsucursal+'"><td><span class="glyphicon glyphicon-remove" onclick="removePrecio('+data[0].id+','+idsucursal+');"></span></td> <td>'+sucursal+'</td> <td>'+data[0].nombre+'</td><td>'+'<input type="number" min="0" id="id_'+data[0].id+'_'+idsucursal+'" value='+parseFloat(precioFinal)+'></input></td></tr>'); 
         }else {            
             $('#preciosList tr:last').after('<tr idListPre="'+data[0].id+'" idsuc="'+idsucursal+'" id="idLp_'+data[0].id+"_"+idsucursal+'"><td><span class="glyphicon glyphicon-remove" onclick="removePrecio('+data[0].id+','+idsucursal+');"></span></td> <td>'+sucursal+'</td> <td>'+data[0].nombre+'</td><td>$'+parseFloat(precioFinal)+'</td></tr>'); 
         }       

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}

function agregaPrecioSucursal(){
    var sucAUX = $("#idsuc").val();
    var idsucursal = $('#selectsucursal').val();
    var sucursal = $('#selectsucursal option:selected').text();
    if(sucAUX > 0){
        idsucursal = $("#idsuc").val();
        sucursal = $("#nomsuc").val();
    }

    var idLista = $('#listaPrecio').val();
    var precioBase = $('#preciosucursal').val();
    var a=1;

    if(idsucursal == 0){
        alert('Debe Seleccionar una sucursal');
        return false;
    }

    $("#preciosSucursal tr").each(function (index) 
    {   
        suc = $(this).attr('idsuc');
        if(suc == idsucursal){
            alert('La suscursal ya se encuentra agregada');
            a=0;
            return false;
        }
    });

    if(a==0){
        return false;
    } 
    
    if (precioBase == '') {
        alert('Debes agregar un precio al producto');
        return false;
    }
    
    if(precioBase  < 0){
        alert('El precio del producto no debe ser negativo.');
        return false;
    }


    $('#preciosSucursal tr:last').after('<tr idsuc="'+idsucursal+'" id="idSp_'+idsucursal+'"><td><span class="glyphicon glyphicon-remove" onclick="removePrecioSucursal('+idsucursal+');"></span></td> <td>'+sucursal+'</td> <td><input type="number" min="0" id="id_'+idsucursal+'" value='+parseFloat(precioBase)+'></input></td></tr>'); 

    
}

function impuestoOnOff(val)
{

    if ($('#chk_'+val).is(':checked'))
    {
        //document.getElementById("impuesto_"+val).readOnly = false;
    }
    else
    {
        document.getElementById("impuesto_"+val).readOnly = true;
    }
}
function removePrecio(idL,idS){

    $('#idLp_'+idL+'_'+idS).remove();
    
}
function removePrecioSucursal(idS){

    $('#idSp_'+idS).remove();
    
}
function removeProve(id){
    $('#idPr_'+id).remove();
}
function removeCarac(id){
    $('#carac_'+id).remove();
}
function muestraIeps(){
    var impuesto  = $('#selectImpuestos').val();

    if(impuesto > 7){
        $('#selectfomula').show();
    }else{
        $('#selectfomula').hide();
        $('#formula > option[value="0"]').attr('selected', 'selected');
    }
}
function back(){
    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=producto&f=indexGridProductos';
}
function escanea(){
    $('#camara').toggle();
}
function servicio(){

   c = $('#tipoProd').val();

   if(c==2){
        $('#servicio').show('slow');
            $('[name="seriesCheck"]').attr('disabled', 'disabled');
            $('[name="pedimentosCheck"]').attr('disabled', 'disabled');
            $('[name="lotesCheck"]').attr('disabled', 'disabled');
            $('[name="unidadesCheck"]').attr('disabled', false);
            $('[name="caracCheck"]').attr('disabled', 'disabled');

            //$('.1').attr('disabled', 'disabled');
            $('.2').attr('disabled', 'disabled');
            $('#costeoSelect').attr('disabled', 'disabled');

            /*$('#costeoSelect > option[value="5"]').attr('selected', 'selected');
            $('#costeoSelect').select2({ width: '350px' }); */

   }else{
        $('#servicio').hide('slow');
        $('#costoServicio').val('');
        $('[name="seriesCheck"]').removeAttr('disabled');
        $('[name="pedimentosCheck"]').removeAttr('disabled');
        $('[name="lotesCheck"]').removeAttr('disabled');
        $('[name="unidadesCheck"]').removeAttr('disabled');
        $('[name="caracCheck"]').removeAttr('disabled');
        
        //$('.1').prop('disabled', false);
        $('.2').prop('disabled', false);
        $('#costeoSelect').prop('disabled', false);

   }
   satTipo();
   
}
function activar(idProducto){
    $('#activaProd').val(idProducto);
        $('#modalActiva').modal({
                show:true,
            });
}
function activar2(){
    var idProducto = $('#activaProd').val();
    $.ajax({
        url: 'ajax.php?c=producto&f=activa',
        type: 'post',
        dataType: 'json',
        data: {idProducto: idProducto},
    })
    .done(function(data) {
        
        console.log(data);
        if(data.estatus==true){
            alert('Se Activo el producto');
            var pathname = window.location.pathname;
            window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=producto&f=indexGridProductos';
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}
function buscaFam(){

    $.ajax({
        url: 'ajax.php?c=producto&f=buscaFam',
        type: 'POST',
        dataType: 'json',
        data: {dep: $('#departamento').val()},
    })
    .done(function(resp1) {
        console.log(resp1);
        $('#familia').empty();
        $.each(resp1, function(index, val) {
           $('#familia').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#familia').select2({width:'100%'});
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
} 
function buscaLinea(){
    
    $.ajax({
        url: 'ajax.php?c=producto&f=buscaLinea',
        type: 'POST',
        dataType: 'json',
        data: {fam: $('#familia').val()},
    })
    .done(function(resp1) {
        console.log(resp1);
        $('#linea').empty();
        $.each(resp1, function(index, val) {
           $('#linea').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#linea').select2({width:'100%'});

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
} 
function mostrarMas(){
       //caja.mensaje('Procesando...')
    var rango = $('#rango').val();
   //$('#modalMensajes').modal();
    $.ajax({
        url: 'ajax.php?c=producto&f=mostrarMas',
        type: 'post',
        dataType: 'json',
        data: {rango: rango},
    })
    .done(function(result) {
 
        var y1 = parseFloat(rango);
        var x1 = y1 + 100;
      
        $('#rango').val(x1);
        console.log(result);
        var table = $('#tableGrid').DataTable();
        var erd = $('#totlaProds').val();
        var ttp = 0;
            //$('.rows').remove();
            
            //table.clear().draw();
         
            var x ='';
            var estatus = '';
            var monto = 0;
            var iva = 0;
            var total = 0;
            var y = '';
            var acuse = '';
            var botones = '';
            var prove = '';
            var cost = 0;
            var empleado = '';
            $.each(result.productos, function(index, val) {

                        
                            if(val.status==1){
                                estatus = '<span class="label label-success">Activo</span>';
                                botones = '<a href="index.php?c=producto&f=index&idProducto='+val.id+'" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a><a href="#" class="btn btn-danger btn-xs active" onclick="borraProducto('+val.id+');"><span class="glyphicon glyphicon-remove"></span> Desactivar</a>';
                            }else{
                                estatus = '<span class="label label-danger">Inactivo</span>';
                                botones = '<a href="#" class="btn btn-info btn-xs active" onclick="activar('+val.id+');"><span class="glyphicon glyphicon-check"></span> Activar</a>';
                            }
                            if(val.idProve==null){
                               prove = '';
                            }else{
                                prove = val.idProve;
                            }
                            if(val.costo==null){
                                cost = 0.00;
                            }else{
                                cost = val.costo;
                            }
                            if(val.idempleado==null){
                                empleado = '';
                            }else{
                                empleado = val.idempleado;
                            }
                            y = '<tr>'+
                             '<td>'+val.id+'</td>'+
                             '<td>'+val.codigo+'</td>'+
                             '<td>'+val.nombre+'</td>'+
                             '<td>$'+parseFloat(val.precio).toFixed(2)+'</td>'+
                             '<td>$'+parseFloat(cost).toFixed(2)+'</td>'+
                             '<td>'+prove+'</td>'+
                             '<td>'+empleado+'</td>'+
                             '<td>'+estatus+'</td>'+
                             '<td>'+botones+'</td>'+
                             '</tr>'

                            total ++;
                            table.row.add($(y)).draw();
                                    
            });    
            ttp = parseFloat(erd) + parseFloat(total); 
            $('#totlaProds').val(ttp);
            $('#totlaProdsLabel').text(ttp);


        
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}
function mostrarTodos(){
       //caja.mensaje('Procesando...')
    var rango = $('#rango').val();
   //$('#modalMensajes').modal();
    $.ajax({
        url: 'ajax.php?c=producto&f=mostrarTodos',
        type: 'post',
        dataType: 'json',
        data: {rango: rango},
    })
    .done(function(result) {
 
        var y1 = parseFloat(result.total);
        var x1 = y1 + rango;
      
        $('#rango').val(x1);
        console.log(result);
        var table = $('#tableGrid').DataTable();
        var erd = $('#totlaProds').val();
        var ttp = 0;

            var x ='';
            var estatus = '';
            var monto = 0;
            var iva = 0;
            var total = 0;
            var y = '';
            var acuse = '';
            var botones = '';
            var prove = '';
            var cost = 0;
            var empleado = '';
            $.each(result.productos, function(index, val) {

                        
                            if(val.status==1){
                                estatus = '<span class="label label-success">Activo</span>';
                                botones = '<a href="index.php?c=producto&f=index&idProducto='+val.id+'" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a><a href="#" class="btn btn-danger btn-xs active" onclick="borraProducto('+val.id+');"><span class="glyphicon glyphicon-remove"></span> Desactivar</a>';
                            }else{
                                estatus = '<span class="label label-danger">Inactivo</span>';
                                botones = '<a href="#" class="btn btn-info btn-xs active" onclick="activar('+val.id+');"><span class="glyphicon glyphicon-check"></span> Activar</a>';
                            }
                            if(val.idProve==null){
                               prove = '';
                            }else{
                                prove = val.idProve;
                            }
                            if(val.costo==null){
                                cost = 0.00;
                            }else{
                                cost = val.costo;
                            }
                            if(val.idempleado==null){
                                empleado = '';
                            }else{
                                empleado = val.idempleado;
                            }
                            y = '<tr>'+
                             '<td>'+val.id+'</td>'+
                             '<td>'+val.codigo+'</td>'+
                             '<td>'+val.nombre+'</td>'+
                             '<td>$'+parseFloat(val.precio).toFixed(2)+'</td>'+
                             '<td>$'+parseFloat(cost).toFixed(2)+'</td>'+
                             '<td>'+prove+'</td>'+
                             '<td>'+empleado+'</td>'+
                             '<td>'+estatus+'</td>'+
                             '<td>'+botones+'</td>'+
                             '</tr>'

                            total ++;
                            table.row.add($(y)).draw();
                                    
            });    
            ttp = parseFloat(erd) + parseFloat(total); 
            $('#totlaProds').val(ttp);
            $('#totlaProdsLabel').text(ttp);


        
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}
function changeConfigComision() {
    if( $('input[name=configComision]:checked').val() == 1 ){
        $('#comisionSubtotal').show();
        $('#comisionUtilidadBruta').hide();
    }
    else if ( $('input[name=configComision]:checked').val() == 2 ) {
        $('#comisionSubtotal').hide();
        $('#comisionUtilidadBruta').show();
    }
    else {
        $('#comisionSubtotal').hide();
        $('#comisionUtilidadBruta').hide();
    }
}

function satTipo(){
    tipoProd = ($('#tipoProd').val() == 2) ? 2 : 1;
    $.ajax({
        url: 'ajax.php?c=producto&f=divisionesSat',
        type: 'POST',
        dataType: 'json',
        data: {tipo: tipoProd},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        $('#divisionSat').empty();
        $('#divisionSat').append('<option value="0">-Selecciona-</option>');
        $.each(resDiv.divisiones, function(index, val) {
           $('#divisionSat').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#divisionSat').select2({width:'100%'});
        $('#divisionSat').val(0);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function satGrupo(){
    $.ajax({
        url: 'ajax.php?c=producto&f=gruposSat',
        type: 'POST',
        dataType: 'json',
        data: {division: $('#divisionSat').val()},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        $('#grupoSat').empty();
        $('#grupoSat').append('<option value="0" >-Selecciona-</option>');
        $.each(resDiv.grupos, function(index, val) {
           $('#grupoSat').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#grupoSat').select2({width:'100%'});
        $('#grupoSat').val(0);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function satClase(){
    $.ajax({
        url: 'ajax.php?c=producto&f=claseSat',
        type: 'POST',
        dataType: 'json',
        data: {grupo: $('#grupoSat').val()},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        $('#claseSat').empty();
        $('#claseSat').append('<option value="0" >-Selecciona-</option>');
        $.each(resDiv.clases, function(index, val) {
           $('#claseSat').append('<option value="'+val.id+'">'+val.nombre+'</option>');
        });
        $('#claseSat').select2({width:'100%'});
        $('#claseSat').val(0);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function satClave(){
    $.ajax({
        url: 'ajax.php?c=producto&f=claveSat',
        type: 'POST',
        dataType: 'json',
        data: {clase: $('#claseSat').val()},
    })
    .done(function(resDiv) {
        console.log(resDiv);
        $('#claveSat').empty();
        $('#claveSat').append('<option value="0" >-Selecciona-</option>');
        $.each(resDiv.claves, function(index, val) {
           $('#claveSat').append('<option value="'+val.c_claveprodserv+'">'+val.c_claveprodserv+' / '+val.descripcion+'</option>');
        });
        $('#claveSat').select2({width:'100%'});
        $('#claveSat').val(0);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}

function asignar_cant_req($objeto){

    $.ajax({
        data : $objeto,
        url : 'ajax.php?c=producto&f=asignar_cant_req',
        type : 'POST',
        dataType : 'json'
    }).done(function(resp) {
        console.log('----> Done asignar_cant_req');
        console.log(resp);
}).fail(function(resp) {
        console.log('----> Fail calcular precio');
        console.log(resp);

        $mensaje = 'Error, no se pueden hacer cambios';
        $.notify($mensaje, {
            position : "top center",
            autoHide : true,
            autoHideDelay : 5000,
            className : 'error',
            arrowSize : 15
        });
    });

}

