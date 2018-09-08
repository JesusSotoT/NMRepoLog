function reloadtable(){

      $.ajax({
        url: 'ajax.php?c=liquidaciones&f=listaOS',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
        var table = $('#table_listado').DataTable({responsive: true});
        table.clear().draw();
        var x ='';
        $.each(data, function(index, val) {
          x ='<tr idConvenio="'+val.idordenservicio+'" class="filas">'+
          '<td>'+val.fecha+'</td>'+
          '<td>'+val.idordenservicio+'</td>'+
          '<td>'+val.idunidad+'</td>'+
          '<td>'+val.viaje+'</td>'+
          '<td>'+val.cliente+'</td>'+
          '<td>'+val.operador+'</td>'+
          '<td>'+val.origen+'</td>'+
          '<td>'+val.destino+'</td>'+
          '<td><a class="btn btn-sm" style="background-color: #559641;"  title="Gasto" onclick="liquidar('+val.idordenservicio+')"><i style="color: white;" class="glyphicon glyphicon-usd"></i></a></td>'+            
          '</tr>';  
          table.row.add($(x)).draw();                           
        });
      }) 

  }


function liquidar(idordenservicio){

    $("#divadd").hide();
    $('#btnback').hide();
    $('#btnnext2').hide();
    $('.thnext').hide();
    $('#countCMBTarg').val('');
    $('#countCMBThermoTarg').val('');
    $("#countCMBEfectivo").val('');
    $("#countCMBThermoEfect").val('');
    $('#pag2').hide();
     datosLiquidacion(idordenservicio);

  }

function validaSiguiente(){
  kmrecorridos = $("#kmrecorridos").val();
  
}


function siguiente(){

    $('#btnAnt').hide();
    $('#btnnext2').show();
    $('#btnnext').hide();
    $('#btnback').show();
    $('.tddel').hide();
    $('#tablaAnticipos').show();
    $('.thnext').show();
    $('#btnclose').hide();
  
    kminicial = $('#kminicial').val();
    kmdescarga = $('#kmdescarga').val();
    kmfinal = $('#kmfinal').val();
     
    if((kminicial >= kmdescarga) || (kmdescarga >= kmfinal)){
    kmrecorridos = $("#kmrecorridos").val();
    $("#kmrecorridos2").val(kmrecorridos).attr('readonly','readonly');
    idsolicitud = $("#idsolicitud").val();
    var sumaAnticipos = $("#sumaAnticipos").text();
    var sumaAnticiposnum = parseInt(sumaAnticipos);
    $('#totant').val(sumaAnticipos).attr('readonly','readonly');
    var sumalit = $("#sumalit").text();
    var sumalitnum = parseFloat(sumalit);
    var sumalitEfectivo = $("#sumalitEfectivo").text();
    var sumalitEfectivonum = parseFloat(sumalitEfectivo);
    litrosconscam =  sumalitnum + sumalitEfectivonum;
    $('#litcons').val(litrosconscam.toFixed(2)).attr('readonly','readonly');
    var sumaThermolit = $("#sumalitherm").text();
    var sumaThermolitnum = parseFloat(sumaThermolit);
    var sumaThermolitEfect = $("#sumalitThermoEfect").text();
    var sumaThermolitEfectnum = parseFloat(sumaThermolitEfect);
    litrosconsThemo = sumaThermolitnum + sumaThermolitEfectnum;
    $('#litconstherm').val(litrosconsThemo.toFixed(2));
    var rendcam = kmrecorridos/litrosconscam;
    $('#rendcam').val(rendcam.toFixed(2)).attr('readonly','readonly');
    rendtherm = kmrecorridos/litrosconsThemo;
    $('#rendtherm').val(rendtherm.toFixed(2)).attr('readonly','readonly');

    var sumacamtarj = $("#sumaCMBTarg").text();
    var sumacamtarjnum = parseInt(sumacamtarj);
    var sumaefect = $("#sumaCMBEfectivo").text();
    var sumacamefectnum = parseInt(sumaefect);
    var sumathermtarj = $("#sumaCMBThermoTarg").text();
    var sumathermtarjnum = parseInt(sumathermtarj);
    var sumathermefect = $("#sumaCMBThermoEfect").text();
    var sumathermefectnum = parseInt(sumathermefect);
    var sumacaseta = $("#sumaCaseta").text();
    var sumacasetanum = parseInt(sumacaseta);
    var sumacasetacliente = $("#sumaCasetaCli").text();
    var sumacasetaclientenum = parseInt(sumacasetacliente);
    var sumagastos = $("#sumaGasto").text();
    var sumagastosnum = parseInt(sumagastos);

    sumaGastos =  sumacamefectnum + sumathermtarjnum + sumathermefectnum + sumacasetanum + sumacasetaclientenum + sumagastosnum;
    $('#gastot').val(sumaGastos.toFixed(2)).attr('readonly','readonly');
    var gastosPsum = $('#gastot').val();
    var totPsum = $('totant').val();
    restPinp = sumaAnticiposnum - sumaGastos;

    sumaGastosEfect =  sumacamefectnum + sumathermefectnum + sumacasetaclientenum ;
    restDevolucion = sumaAnticiposnum - sumaGastosEfect;
    resTotal = restDevolucion - restPinp;
    $('#devtot').val(restDevolucion.toFixed(2)).attr('readonly','readonly');
    $('#diftot').val(restPinp.toFixed(2)).attr('readonly','readonly');
    $('#restot').val(resTotal.toFixed(2)).attr('readonly','readonly');


    var promtarj = $("#promedioCMBTarj").text();
    var promtajrnum = parseFloat(promtarj);
    var promefect = $("#promedioEfect").text();
    var promejectnum = parseFloat(promefect);
    var promtherm = $('#promedioCMBThermoTarj').text();
    var promthermnum = parseInt(promtherm);
    var promthermefect = $('#promedioThermoEfect').text();
    var promthermefectnum = parseInt(promthermefect);


    promedioCMB = promtajrnum + promejectnum;
    promedioCMBtotal = promedioCMB / 2;

    promedioCMBtherm = promthermnum + promthermefectnum;
    promedioCMBthermTotal = promedioCMBtherm / 2;

 


/// Trae los datos del rendmiento de la unidad y valida si es local o foraneo para calcular el rendimiento definido en el alta de unidades..
      $.ajax({
          data:{idsolicitud:idsolicitud},
          url: 'ajax.php?c=Liquidaciones&f=listarendimientos',
          type: 'POST',
          dataType: 'json',
      })
      .done(function(data) {
          var rendimientoForaneo = 0;
          var rendimientoLocal = 0;
          var tanque_tamano = 0;
          var tipo_viaje = 0;
      $.each(data, function(index, val) {

        rendimientoForaneo = val.tanque_rendimiento_foraneo;
        rendimientoLocal = val.tanque_rendimiento_local;
        tanque_tamano = val.tanque_tamano;
        tipo_viaje = val.viaje;
        rendthermForaneo = val.tanque_rendimiento_foraneo_thermo;
        rendthermLocal =  val.tanque_rendimiento_local_thermo;

        if(tipo_viaje == "Foraneo"){
          porcentaje_camion =  rendcam / rendimientoForaneo;
          total_porcentaje_camion = porcentaje_camion * 100;
          $('#porcencam').val(total_porcentaje_camion.toFixed(2)).attr('readonly','readonly');

          porcentaje_thermo = rendtherm / rendthermForaneo;
          total_porcentaje_thermo = porcentaje_thermo * 100;
          $('#porcenttherm').val(total_porcentaje_thermo.toFixed(2)).attr('readonly','readonly');

        }

        if(tipo_viaje == "Local"){
          porcentaje_camion = rendcam / rendimientoLocal;
          total_porcentaje_camion = porcentaje_camion * 100;
          $('#porcencam').val(total_porcentaje_camion.toFixed(2)).attr('readonly','readonly');

           porcentaje_thermo = rendtherm / rendthermLocal;
          total_porcentaje_thermo = porcentaje_thermo * 100;
          $('#porcenttherm').val(total_porcentaje_thermo.toFixed(2)).attr('readonly','readonly');

         }

         var restaideal = litrosconscam - total_porcentaje_camion;
       var divideideal =  kmrecorridos / restaideal;
       var ajusteideal = divideideal * promedioCMBtotal;
       var restathermoideal = litrosconsThemo - total_porcentaje_thermo;
       var divideidealthermo = kmrecorridos / restathermoideal;
       var ajustethermoideal = divideidealthermo * promedioCMBthermTotal;
       $('#ajusxltspc').val(ajusteideal.toFixed(2)).attr('readonly','readonly');
      $('#ajuscltsth').val(ajustethermoideal.toFixed(2)).attr('readonly','readonly');
    });
})


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////Lista los convenios y los muestra en una tabla///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $.ajax({
          data:{idsolicitud,idsolicitud},
          url: 'ajax.php?c=Liquidaciones&f=listaConvenios',
          type: 'POST',
          dataType: 'json',
      })
      .done(function(data) {
        var comision =0,sueldo = 0;

          var table = $('#tabla_convenios').DataTable({
                        "bPaginate": false,
                        "bLengthChange": false,
                        "bFilter": false,
                        "bInfo": false,
                        "bAutoWidth": false,
                        "bDestroy": true, /// permite destruit al volver a recargar
                        "responsive": true
                        });

          table.clear().draw();
          var x ='';
          $.each(data, function(index, val) {
            comision = val.comision_porcentual*1;
            precio_cliente = val.precio_cliente*1;
            if(comision > 0){
              sueldo = (precio_cliente * comision) / 100;
            }else{
              sueldo = 0;
            }
            
            x ='<tr>'+
            '<td>'+val.concepto+'</td>'+
            '<td>'+precio_cliente+'</td>'+
            '<td>'+sueldo+'</td>'+
            '</tr>';  
            table.row.add($(x)).draw();                           
          });
        })

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


      $('#kminicial');
      $('#kmdescarga');
      $('#kmfinal');
      $('#hrinicial').attr('readonly','readonly');
      $('#hrfinal');
      $('#tablaAnticipos').show();
      $('#pag2').show();
      }else{

      $('#btnnext2').show();
      $('#btnnext').hide();
      $('#btnback').show();
      $('.tddel').hide();
      $('#tablaAnticipos').hide();
      $('.thnext').show();
      
      kmrecorridos = $("#kmrecorridos").val();
      
      $("#kmrecorridos2").val(kmrecorridos);
      $("#litcons").val(litcons);
      
      idsolicitud = $("#idsolicitud").val();

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $.ajax({
          data:{idsolicitud,idsolicitud},
          url: 'ajax.php?c=Liquidaciones&f=listaConvenios',
          type: 'POST',
          dataType: 'json',
      })
      .done(function(data) {
        var comision =0,sueldo = 0;

          var table = $('#tabla_convenios').DataTable({
                      "bPaginate": false,
                      "bLengthChange": false,
                      "bFilter": false,
                      "bInfo": false,
                      "bAutoWidth": true,
                      "bDestroy": true, /// permite destruit al volver a recargar
                      "responsive": true
                      });
          table.clear().draw();
          var x ='';
          $.each(data, function(index, val) {
            comision = val.comision_porcentual*1;
            precio_cliente = val.precio_cliente*1;
            if(comision > 0){
            
              sueldo = (precio_cliente * comision) / 100;
            
            }else{
            
              sueldo = 0;

            }     
           x ='<tr">'+
              '<td>'+val.concepto+'</td>'+
              '<td>'+precio_cliente+'</td>'+
              '<td>'+sueldo+'</td>'+
              '</tr>';  
            table.row.add($(x)).draw();                           
          });
        })

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


      $('#kmdescarga');
      $('#hrinicial').attr('readonly','readonly');
      $('#hrfinal');
      $('#pag2').show();
    } 

  }

function registroliquidacion(){  
   
    idsolicitud = $("#idsolicitud").val();
    idordenservicio = $("#idordenservicio").val();
    idcliente = $("#idcliente").val();

                 $.ajax({
                      data:{idsolicitud:idsolicitud,idordenservicio:idordenservicio,idcliente:idcliente},
                      url: 'ajax.php?c=Liquidaciones&f=Save1partliq',
                      type: 'POST',
                      dataType: 'text',
                  })
                 .done(function(data) {

                                  })
  }

function regresar(){

    $('#btnnext').show();
    $('#btnback').hide();
    $('#btnnext2').hide();
    $('.tddel').show();
    $('.thnext').hide();
    $('#pag2').hide();
    $('#btnclose').show();
    $('#btnAnt').show();

  }


function cerrar(){
    $('#btnAnt').show();
    $("#divliquidar").hide();
    $("#divadd").show();

  }


function addAnticipos(idordenservicio){

    $('#modal_add_anticipo').modal('show');
    $('#Anoperador').html(' ');
    $('#Anformapago').html(' ');
    $('#Anformapago').append('<option value="0">Selecciona la Forma de Pago</option>');
    $('#Ancuenta').html(' ');
    $('#Ancuenta').append('<option value="0">Selecciona una Cuenta</option>');
    $('#Anreferencia').val(' ');
    $('#Animporte').val(' ');
    
    idordenservicio = $("#idordenservicio").val();
    
    $("#AnOS").val(idordenservicio).attr('readonly','readonly');
    $("#Anfecha").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
    $("#Anfecha").datepicker( "setDate" , hoy());
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
    $.ajax({
        data:{idordenservicio,idordenservicio},
        url: 'ajax.php?c=Liquidaciones&f=datosAnticipoOperador',
        type: 'POST',
        dataType: 'json',
    })
    .done(function(data){
        $.each(data, function(index, val) {
           $('#Anoperador').append('<option value="'+val.idEmpleado+'">'+val.operador+'</option>').attr('readonly','readonly');  
        });
      })
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $.ajax({
        url: 'ajax.php?c=Liquidaciones&f=listaFormaspago',
        type: 'POST',
        dataType: 'json',
    })
    .done(function(data) {
        $.each(data, function(index, val) {
            $('#Anformapago').append('<option value="'+val.idFormapago+'">'+val.nombre+'</option>');  
        });
    })
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $.ajax({
        url: 'ajax.php?c=Liquidaciones&f=listaCuenta',
        type: 'POST',
        dataType: 'json',
    })
    .done(function(data) {
        $.each(data, function(index, val) {
            $('#Ancuenta').append('<option value="'+val.idbancaria+'">'+val.cuenta+'</option>');  
        });
    })
  }






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






function addCMBTarg(){

    sumaCMBTarg = $("#sumaCMBTarg").text();
    sumaCMBTarg = sumaCMBTarg *1;
    CMBTargconut = $("#countCMBTarg").val();
    CMBTargconut = CMBTargconut * 1;
    CMBTargconut1 = CMBTargconut + 1;
    $("#countCMBTarg").val(CMBTargconut1);
    CMBTargcostlit = $("#inpCostlitTarg").val();
    inpNoValeCMBTarg = $("#inpNoValeCMBTarg").val();
    inpCantCMBTarg = $("#inpCantCMBTarg").val();
    litXcant = inpCantCMBTarg / CMBTargcostlit ;
    idordenservicio = $("#idordenservicio").val();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if (inpNoValeCMBTarg == '' || inpCantCMBTarg == ''){
      alert("Llene los campos obligatorios");
      $("#inpNoValeCMBTarg").val('');
      $("#inpCantCMBTarg").val('');
      $("#inpCostlitTarg").val('');
    }else{
      $.ajax({
        data: {idordenservicio:idordenservicio,inpNoValeCMBTarg:inpNoValeCMBTarg,inpCantCMBTarg:inpCantCMBTarg,CMBTargcostlit:CMBTargcostlit,litXcant:litXcant},
        url: 'ajax.php?c=Liquidaciones&f=SaveCMBTarg',
        type: 'POST',
        dataType: 'json',
      })

      $('#tablaCMBTarg tbody tr:last').after('<tr class="filaCMBTarg tabla-listas2" id = "filaCMBTarg'+CMBTargconut1+'"><td>'+inpNoValeCMBTarg+'</td><td>'+inpCantCMBTarg+'</td><td>'+CMBTargcostlit+'</td><td id="sumalos">'+litXcant.toFixed(2)+'</td><td class = "tddel" id="countCMBTarg'+CMBTargconut1+'"><button class="btn-sm btn btn-danger" id="btndelCMBTarg'+CMBTargconut1+'" onclick="deleteFilaCMBTarg('+CMBTargconut1+')">Eliminar</button></td></tr>');
      $("#inpNoValeCMBTarg").val('');
      $("#inpCantCMBTarg").val('');
      $("#inpCostlitTarg").val('');


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        var i = 0;
        suma = 0;
        var campo1 = 0, campo2 = 0 , campo3 = 0 ,campo4 = 0;
        $("#tablaCMBTarg tbody tr").each(function (index) 
        {

            i++;
            $(this).children("td").each(function (index2){

            switch (index2) 
            {
                case 0: campo1 += Number($(this).text());
                        break;

                case 1: campo2 += Number($(this).text());
                        break;

                case 3: campo3 += Number($(this).text());
                        break;

                case 2: campo4 += Number($(this).text());
                        break;
            }})
        })

        promedio = campo4 / CMBTargconut1;
        $("#inpNoValeCMBTarg").val('');
        $("#inpCantCMBTarg").val('');
        $("#promedioCMBTarj").text(promedio.toFixed(2));
        $("#sumaCMBTarg").text(campo2.toFixed(2)).val(campo2);
        $("#sumalit").text(campo3.toFixed(2)).val(campo3);
    
    }// fin de else
  }



function deleteFilaCMBTarg(id){
        
        $("#filaCMBTarg"+id).remove(); /// se elimina y se hace el proceso para la suma
        var i = 0;
        suma = 0;
        var campo1 = 0, campo2 = 0 , campo3 = 0 ,campo4 = 0;
        $("#tablaCMBTarg tbody tr").each(function (index) 
        {
            i++;
            $(this).children("td").each(function (index2) 
            {
            switch (index2) 
            {
                case 0: campo1 += Number($(this).text());
                        break;
                case 1: campo2 += Number($(this).text());
                        break;
                case 3: campo3 += Number($(this).text());
                        break;
                case 2: campo4 += Number($(this).text());
                        break;
            }
          })
        })

        promedio = campo4 / CMBTargconut1;
        $("#inpNoValeCMBTarg").val('');
        $("#inpCantCMBTarg").val('');
        $("#promedioCMBThermoTarj").text(promedio.toFixed(2));
        $("#sumaCMBTarg").text(campo2.toFixed(2)).val(campo2);
        $("#sumalit").text(campo3.toFixed(2)).val(campo3);
  }


function addCMBThermoTarg(){

    sumaCMBThermoTarg = $("#sumaCMBThermoTarg").text();
    sumaCMBThermoTarg = sumaCMBThermoTarg *1;
    CMBThermoTargconut = $("#countCMBThermoTarg").val();
    CMBThermoTargconut = CMBThermoTargconut * 1;
    CMBThermoTargconut1 = CMBThermoTargconut+ 1;
    $("#countCMBThermoTarg").val(CMBThermoTargconut1);
    inpCostlitTargTher = $('#inpCostlitTargTher').val();
    inpNoValeCMBThermoTarg = $("#inpNoValeCMBThermoTarg").val();
    inpCantCMBThermoTarg = $("#inpCantCMBThermoTarg").val();
    litxcant = inpCantCMBThermoTarg / inpCostlitTargTher ; 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if (inpNoValeCMBThermoTarg == '' || inpCantCMBThermoTarg == ''){
        alert("Llene los campos");

    }else{

      $('#tablaCMBThermoTarg tbody tr:last').after('<tr class="filaCMBThermoTarg" id = "filaCMBThermoTarg'+CMBThermoTargconut1+'"><td>'+inpNoValeCMBThermoTarg+'</td><td>'+inpCantCMBThermoTarg+'</td><td>'+inpCostlitTargTher+'</td><td>'+litxcant.toFixed(2)+'</td><td class = "tddel" id="countCMBThermoTarg'+CMBThermoTargconut1+'"><button class="btn-sm btn btn-danger" id="btndelCMBThermoTarg'+CMBThermoTargconut1+'" onclick="deleteFilaCMBThermoTarg('+CMBThermoTargconut1+')" type="button">Eliminar</button></td></tr>');
      $("#inpNoValeCMBThermoTarg").val('');
      $("#inpCantCMBThermoTarg").val('');
      $('#inpCostlitTargTher').val('');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        var i = 0;
        suma = 0;
        var campo1 = 0, campo2 = 0 , campo3 = 0, campo4= 0;
        $("#tablaCMBThermoTarg tbody tr").each(function (index) 
        {  
        i++;
        $(this).children("td").each(function (index2) 
        {
        switch (index2) 
        {
                case 0: campo1 += Number($(this).text());
                        break;
                case 1: campo2 += Number($(this).text());
                        break;
                case 3: campo3 += Number($(this).text());
                        break;
                case 2: campo4 += Number($(this).text());
                        break;
        }
        })
        })

        console.log(campo4);

        promedio = campo4 / CMBThermoTargconut1;
        $("#inpNoValeCMBThermoTarg").val('');
        $("#inpCantCMBThermoTarg").val('');
        $("#promedioCMBThermoTarj").text(promedio.toFixed(2));
        $("#sumaCMBThermoTarg").text(campo2.toFixed(2)).val(campo2);
        $("#sumalitherm").text(campo3.toFixed(2)).val(campo3);

    }//else
  }




function deleteFilaCMBThermoTarg(id){

    $("#filaCMBThermoTarg"+id).remove();
    /// se elimina y se hace el proceso para la suma
       var i = 0;
        suma = 0;
        var campo1 = 0, campo2 = 0 , campo3 = 0, campo4= 0;
        $("#tablaCMBThermoTarg tbody tr").each(function (index) 
        {  
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 += Number($(this).text());
                            break;
                    case 1: campo2 += Number($(this).text());
                            break;
                    case 3: campo3 += Number($(this).text());
                            break;
                    case 2: campo4 += Number($(this).text());
                            break;
                }
            })
        })
        console.log(campo4);
        promedio = campo4 / CMBThermoTargconut1;
        $("#inpNoValeCMBThermoTarg").val('');
        $("#inpCantCMBThermoTarg").val('');
        $("#promedioCMBThermoTarj").text(promedio.toFixed(2));
        $("#sumaCMBThermoTarg").text(campo2.toFixed(2)).val(campo2);
        $("#sumalitherm").text(campo3.toFixed(2)).val(campo3);
  }


function addCMBEfectivo(){

    sumaCMBEfectivo = $("#sumaCMBEfectivo").text();
    sumaCMBEfectivo = sumaCMBEfectivo *1;
    CMBEfectivoconut = $("#countCMBEfectivo").val();
    CMBEfectivoconut = CMBEfectivoconut * 1;
    CMBEfectivoconut1 = CMBEfectivoconut+ 1;
    $("#countCMBEfectivo").val(CMBEfectivoconut1);
    inpCostlitEfectivo = $('#inpCostlitEfectivo').val();
    inpCantCMBEfectivo = $("#inpCantCMBEfectivo").val();
    litxcantEfectivo = inpCantCMBEfectivo / inpCostlitEfectivo ;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    if (inpCantCMBEfectivo == '' || inpCostlitEfectivo == ''){
       alert("Llene los campos");
    
    }else{

      $('#tablaCMBEfectivo tbody tr:last').after('<tr class="filaCMBEfectivo" id ="filaCMBEfectivo'+CMBEfectivoconut1+'"><td></td><td>'+inpCantCMBEfectivo+'</td><td>'+inpCostlitEfectivo+'</td><td>'+litxcantEfectivo.toFixed(2)+'</td><td class = "tddel" id="countCMBEfectivo'+CMBEfectivoconut1+'"><button class="btn-sm btn btn-danger" id="btndelCMBEfectivo'+CMBEfectivoconut1+'" onclick="deleteFilaCMBEfectivo('+CMBEfectivoconut1+')" type="button">Eliminar</button></td></tr>');
      $("#inpCantCMBEfectivo").val('');
      $('#inpCostlitEfectivo').val('');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        var i = 0;
        suma = 0;
        var campo1 = 0 , campo2 = 0 , campo3 = 0 , campo4 = 0;
        $("#tablaCMBEfectivo tbody tr").each(function (index) 
        {  
        i++;
        $(this).children("td").each(function (index2) 
        {
        switch (index2) 
        {
          case 1: campo2 += Number($(this).text());
                  break;
          case 2: campo1 += Number($(this).text());
                  break;
          case 3: campo3 += Number($(this).text());
                  break;
        }

        })

        })

        promedio = campo1 / CMBEfectivoconut1;
        $("#inpCantCMBEfectivo").val('');
        $("#promedioEfect").text(promedio.toFixed(2));
        $("#sumaCMBEfectivo").text(campo2.toFixed(2)).val(campo2);
        $("#sumalitEfectivo").text(campo3.toFixed(2)).val(campo3);

    }//else
  }


function deleteFilaCMBEfectivo(id){

        $("#filaCMBEfectivo"+id).remove(); /// se elimina y se hace el proceso para la suma
        var i = 0;
        suma = 0;
        var campo1 = 0 , campo2 = 0 , campo3 = 0 , campo4 = 0;
        $("#tablaCMBEfectivo tbody tr").each(function (index) 
        {  
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                  case 1: campo2 += Number($(this).text());
                          break;
                  case 2: campo1 += Number($(this).text());
                          break;
                  case 3: campo3 += Number($(this).text());
                          break;
                }
            })
        })
        promedio = campo1 / CMBEfectivoconut1;
        $("#inpCantCMBEfectivo").val('');
        $("#promedioThemorEfect").text(promedio.toFixed(2));
        $("#sumaCMBEfectivo").text(campo2.toFixed(2)).val(campo2);
        $("#sumalitEfectivo").text(campo3.toFixed(2)).val(campo3);
  }


function addCMBThermoEfectivo(){

    sumaCMBThermoEfect = $("#sumaCMBThermoEfect").text();
    sumaCMBThermoEfect = sumaCMBThermoEfect *1;
    CMBThermoEfectconut = $("#countCMBThermoEfect").val();
    CMBThermoEfectconut = CMBThermoEfectconut * 1;
    CMBThermoEfectconut1 = CMBThermoEfectconut+ 1;
    $("#countCMBThermoEfect").val(CMBThermoEfectconut1);
    inpCostlitThermoEfect = $('#inpCostlitThermoEfect').val();
    inpCantCMBThermoEfect = $("#inpCantCMBThermoEfect").val();
    litxcantThermoEfect = inpCantCMBThermoEfect / inpCostlitThermoEfect; 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if (inpCantCMBThermoEfect == '' || inpCostlitThermoEfect == ''){
      alert("Llene los campos");
    }else{
      
      $('#tablaCMBThermoEfect tbody tr:last').after('<tr class="filaCMBThermoEfect" id ="filaCMBThermoEfect'+CMBThermoEfectconut1+'"><td></td><td>'+inpCantCMBThermoEfect+'</td><td>'+inpCostlitThermoEfect+'</td><td>'+litxcantThermoEfect.toFixed(2)+'</td><td class = "tddel" id="countCMBThermoEfect'+CMBThermoEfectconut1+'"><button class="btn-sm btn btn-danger" id="btndelCMBThermoEfect'+CMBThermoEfectconut1+'" onclick="deleteFilaCMBThermoEfectivo('+CMBThermoEfectconut1+')" type="button">Eliminar</button></td></tr>');
      $("#inpCantCMBThermoEfect").val('');
      $('#inpCostlitThermoEfect').val('');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      
        var i = 0;
        suma = 0;
        var campo1 = 0 , campo2 = 0 , campo3 = 0 , campo4 = 0;
        
        $("#tablaCMBThermoEfect tbody tr").each(function (index) 
        {  
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 1: campo2 += Number($(this).text());
                            break;
                    case 2: campo1 += Number($(this).text());
                            break;
                    case 3: campo3 += Number($(this).text());
                    
                }

            })

        })
          promedio = campo1 / CMBThermoEfectconut1;
        $("#inpCantCMBEfectivo").val('');
        $("#promedioThermoEfect").text(promedio.toFixed(2));
        $("#sumaCMBThermoEfect").text(campo2.toFixed(2)).val(campo2);
        $("#sumalitThermoEfect").text(campo3.toFixed(2)).val(campo3);

    }//else
  }



function deleteFilaCMBThermoEfectivo(id){

        $("#filaCMBThermoEfectivo"+id).remove(); /// se elimina y se hace el proceso para la suma
        var i = 0;
        suma = 0;
        var campo1 = 0,campo3 = 0;
        $("#tablaCMBThermoEfect tbody tr").each(function (index) 
        {  
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 += Number($(this).text());
                            break;
                    case 2: campo3 += Number($(this).text());
                            break;
                    
                }

            })

        })
        $("#inpCantCMBThermoEfect").val('');
        $("#sumaCMBThermoEfect").text(campo1.toFixed(2)).val(campo1);
        $("#sumalitThermoEfect").text(campo3.toFixed(2)).val(campo3);
  }



function addCaseta(){

    sumaCaseta = $("#sumaCaseta").text();
    sumaCaseta = sumaCaseta *1;
    Casetaconut = $("#countCaseta").val();
    Casetaconut = Casetaconut * 1;
    Casetaconut1 = Casetaconut + 1;
    $("#countCaseta").val(Casetaconut1);
    //alert(conut1);
    inpCantCaseta = $("#inpCantCaseta").val();
    if (inpCantCaseta == ''){
      alert("Llene los campos");
    }else{
      $('#tablaCaseta tbody tr:last').after('<tr class="filaCaseta" id = "filaCaseta'+Casetaconut1+'"><td>'+inpCantCaseta+'</td><td class = "tddel" id="countCaseta'+Casetaconut1+'"><button class="btn-sm btn btn-danger" id="btndelCaseta'+Casetaconut1+'" onclick="deleteFilaCaseta('+Casetaconut1+')" type="button">Eliminar</button></td></tr>');
      $("#inpCantCaseta").val('');

        var i = 0;
        suma = 0;
        $("#tablaCaseta tbody tr").each(function (index) 
        {
            var campo1;
            i++;
            $(this).children("td").each(function (index2) 
            {
              switch (index2) 
                {
              case 0: campo1 = $(this).text();
                      break;
                }
            })
  
            if(i > 1){
              campo1 = campo1*1;
              suma +=campo1;
  
         }  
        })
        $("#inpCantCaseta").val('');
        $("#sumaCaseta").text(suma);

    }//else
  }


function deleteFilaCaseta(id){

        $("#filaCaseta"+id).remove(); /// se elimina y se hace el proceso para la suma
        var i = 0;
        suma = 0;
        $("#tablaCaseta tbody tr").each(function (index) 
        {
            var campo1;
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                }
            })
            if(i > 1){
              campo1 = campo1*1;
              suma +=campo1;
            }  
        })
        $("#inpCantCaseta").val('');
        $("#sumaCaseta").text(suma);
  }


function addCasestaCli(){

    sumaCasetaCli = $("#sumaCasetaCli").text();
    sumaCasetaCli = sumaCasetaCli *1;
    CasetaconutCli = $("#countCasetaCli").val();
    CasetaconutCli = CasetaconutCli * 1;
    CasetaCliconut1 = CasetaconutCli + 1;
    $("#countCasetaCli").val(CasetaCliconut1);
    //alert(conut1);
    inpCantCasetaCli = $("#inpCantCasetaCli").val();
    if (inpCantCasetaCli == ''){
      alert("Llene los campos");
    }else{
      $('#tablaCasetaCli tbody tr:last').after('<tr class="filaCasetaCli" id = "filaCasetaCli'+CasetaCliconut1+'"><td>'+inpCantCasetaCli+'</td><td class = "tddel" id="countCasetaCli'+CasetaCliconut1+'"><button class="btn-sm btn btn-danger" id="btndelCasetaCli'+CasetaCliconut1+'" onclick="deleteFilaCasetaCli('+CasetaCliconut1+')" type="button">Eliminar</button></td></tr>');
      $("#inpCantCasetaCli").val('');

        var i = 0;
        suma = 0;
        $("#tablaCasetaCli tbody tr").each(function (index) 
        {
            var campo1;
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                }
            })
            if(i > 1){
              campo1 = campo1*1;
              suma +=campo1;
            }  
        })
        $("#inpCantCasetaCli").val('');
        $("#sumaCasetaCli").text(suma);

    }//else
  }


function deleteFilaCasetaCli(id){
        $("#filaCasetaCli"+id).remove(); /// se elimina y se hace el proceso para la suma
        var i = 0;
        suma = 0;
        $("#tablaCasetaCli tbody tr").each(function (index) 
        {
            var campo1;
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                }
            })

            if(i > 1){
              campo1 = campo1*1;
              suma +=campo1;
            }  
        })
        $("#inpCantCasetaCli").val('');
        $("#sumaCasetaCli").text(suma);
  }


function addgasto(){ 

    sumaGasto = $("#sumaGasto").text();
    sumaGasto = sumaGasto *1;
    Gastoconut = $("#countGasto").val();
    Gastoconut = Gastoconut * 1;
    Gastoconut1 = Gastoconut + 1;
    $("#countGasto").val(Gastoconut1);
    //alert(conut1);
    idGasto = $("#idGasto").val();
    concepto = $( "#idGasto option:selected" ).text();
    inpCantidadGasto = $("#inpCantidadGasto").val();
    if (inpCantidadGasto == ''){
      alert("Llene los campos");
    }else{
      $('#tablagastos tbody tr:last').after('<tr class="filaGasto" id = "filaGasto'+Gastoconut1+'"><td>'+concepto+'</td><td>'+idGasto+'</td><td>'+inpCantidadGasto+'</td><td class = "tddel" id="countGasto'+Gastoconut1+'"><button class="btn-sm btn btn-danger" id="btndelGasto'+Gastoconut1+'" onclick="deleteFilaGasto('+Gastoconut1+')" type="button">Eliminar</button></td></tr>');
      $("#idGasto").val(1);
      $("#inpCantidadGasto").val('');

        var i = 0;
        suma = 0;
        $("#tablagastos tbody tr").each(function (index) 
        {
            var campo1, campo2, campo3;
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                    case 1: campo2 = $(this).text();
                            break;
                    case 2: campo3 = $(this).text();
                            break;
                }
            })

            if(i > 1){
              campo3 = campo3*1;
              suma +=campo3;
            }  
        })
        $("#sumaGasto").text(suma);

        
    }//else
  }


function deleteFilaGasto(id){

   $("#filaGasto"+id).remove(); /// se elimina y se hace el proceso para la suma  
        var i = 0;
        suma = 0;
        $("#tablagastos tbody tr").each(function (index) 
        {
            var campo1, campo2, campo3;
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                    case 1: campo2 = $(this).text();
                            break;
                    case 2: campo3 = $(this).text();
                            break;
                }
            })
            if(i > 1){
              campo3 = campo3*1;
              suma +=campo3;
            }  
        })
        $("#sumaGasto").text(suma);
  }



function listaGastosConcepto(){
      $.ajax({
        url: 'ajax.php?c=Liquidaciones&f=listaGastosConcepto',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
        $.each(data, function(index, val) {
          $('#idGasto').append('<option value="'+val.id+'">'+val.concepto+'</option>');  
        });
      })
  }



function listaAnticipos(idordenservicio){
      $.ajax({
        data:{idordenservicio:idordenservicio},
        url: 'ajax.php?c=Liquidaciones&f=listaAnticipos',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
        var suma = 0;
        var count = 0;
        var table = $('#tablaAnticipos').DataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bInfo": false,
                    "bAutoWidth": false,
                    "bDestroy": true /// permite destruit al volver a recargar
                  });

        table.clear().draw();
        var x ='';
        $.each(data, function(index, val) {
          if(val.estatus == 1){
            count = count + 1;
          }
            suma += (val.importe*1);
                x ='<tr">'+
                '<td>'+val.referencia+'</td>'+
                '<td>'+val.fecha_captura+'</td>'+
                '<td>'+val.importe+'</td>'+
                '</tr>';  
                table.row.add($(x)).draw();
      });

        if(count > 0){
           alert("Tiene Anticipo sin autorizar");
           $("#divadd").show();
         }else{
          $("#sumaAnticipos").text(suma);
          // era la funcion datosLiq..
          $.ajax({
            data:{idordenservicio,idordenservicio},
            url: 'ajax.php?c=Liquidaciones&f=datosLiquidacion',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data){
            $.each(data, function(index, val) {
                $("#operador").val(val.operador).attr('readonly','readonly');
                $("#unidad").val(val.unidad).attr('readonly','readonly');
                $("#idordenservicio").val(val.idordenservicio).attr('readonly','readonly');
                $("#cliente").val(val.nombretienda).attr('readonly','readonly');
                $("#fecha").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
                $("#fecha").datepicker( "setDate" , hoy() );
                $("#remite").val(val.carga_en).attr('readonly','readonly');
                $("#origen").val(val.origen).attr('readonly','readonly');
                $("#destintario").val(val.entrega_en).attr('readonly','readonly');
                $("#destino").val(val.destino).attr('readonly','readonly');
                $("#temperatura").val(val.temperatura).attr('readonly','readonly');
                $("#capacidad").val(val.capacidad).attr('readonly','readonly');
                $("#idsolicitud").val(val.idsolicitud).attr('readonly','readonly');

                $("#kminicial").val(val.kmtotal);
                $("#kmdescarga").val(val.kmtotal);
                $("#kmfinal").val(val.kmtotal);
            });

              $("#divliquidar").show();
          })
          
         }
      })
  }
  



function datosLiquidacion(idordenservicio){
      $.ajax({
        data:{idordenservicio,idordenservicio},
        url: 'ajax.php?c=Liquidaciones&f=datosLiquidacion',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data){
        $.each(data, function(index, val) {

            $("#operador").val(val.operador).attr('readonly','readonly');
            $("#unidad").val(val.unidad).attr('readonly','readonly');
            $("#idordenservicio").val(val.idordenservicio).attr('readonly','readonly');
            $("#cliente").val(val.nombretienda).attr('readonly','readonly');
            $("#idcliente").val(val.id).attr('readonly','readonly');
            $("#fecha").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
            $("#fecha").datepicker( "setDate" , hoy() );
            $("#remite").val(val.carga_en).attr('readonly','readonly');
            $("#origen").val(val.origen).attr('readonly','readonly');
            $("#destintario").val(val.entrega_en).attr('readonly','readonly');
            $("#destino").val(val.destino).attr('readonly','readonly');
            $("#temperatura").val(val.temperatura).attr('readonly','readonly');
            $("#capacidad").val(val.capacidad).attr('readonly','readonly');
            $("#idsolicitud").val(val.idsolicitud).attr('readonly','readonly');
            $("#kminicial").val(val.kmtotal).attr('readonly','readonly');
            $("#kmdescarga").val('');
            $("#kmfinal").val('');

            listaAnticipos(idordenservicio);

        });
      })
      registroliquidacion();
      $("#divliquidar").show();
  }
  
function calculos(){
    kminicial = $("#kminicial").val();
    kmdescarga = $("#kmdescarga").val();
    kmfinal = $("#kmfinal").val();
    kmrecorridos = kmfinal - kminicial;
    $("#kmrecorridos").val(kmrecorridos).attr('readonly','readonly');
    kmcargado = kmdescarga - kminicial;
    $("#kmcargado").val(kmcargado).attr('readonly','readonly');
    kmvacio = kmfinal - kmdescarga;
    $("#kmvacio").val(kmvacio).attr('readonly','readonly');
    hrinicial = $("#hrinicial").val();
    hrfinal = $("#hrfinal").val();
    hrtotal = hrfinal - hrinicial;
    $("#hrtotal").val(hrtotal);
  }


function  save_anticipo(){
    Anfecha = $("#Anfecha").val();
    Anoperador = $("#Anoperador").val();
    AnOS = $("#AnOS").val();
    Anformapago = $("#Anformapago").val();
    Anfecha = $("#Anfecha").val();
    Ancuenta = $("#Ancuenta").val();
    Anreferencia = $("#Anreferencia").val();
    Animporte = $("#Animporte").val();

    $.ajax({
        data:{Anfecha,Anfecha,Anoperador:Anoperador,AnOS:AnOS,Anformapago:Anformapago,Anfecha:Anfecha,Ancuenta:Ancuenta,Anreferencia:Anreferencia,Animporte:Animporte},
        url: 'ajax.php?c=Liquidaciones&f=saveAnticipo',
        type: 'POST',
        dataType: 'json',
    })
    .done(function(data) {
      $('#modal_add_anticipo').modal('hide');
      listaAnticipos(AnOS);
      if(data == 1){
        //alert("Registro Exitoso");
      }else{
        alert("Registro Fallido");
      }
    })
    
  }


function hoy(){
       var hoy = new Date();
       var dd = hoy.getDate();
       var mm = hoy.getMonth()+1; //January is 0!
       var yyyy = hoy.getFullYear();
    
    if(dd<10){
        dd='0'+dd
    }
    if(mm<10){
        mm='0'+mm
    }
     var hoy = yyyy+'-'+mm+'-'+dd;
     return hoy;
    }  