   
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

    function pantallaElectronica(estatus){
      $("#divreporteador").hide();
      $("#tablaHistoria").hide();
      var table = $('#table_listaPantalla').DataTable();
      table.clear().draw();
      reloadtable(estatus);

    }
    function pantallaHistorico(estatus){
      $("#divreporteador").hide();
      $("#tablaPantalla").hide();

      $('#idclie').html(' ');
      $('#idclie').append('<option value="0">Selecciona un Cliente</option>');
      $('#idcap').html(' ');
      $('#idcap').append('<option value="0">Selecciona una Capacidad</option>');
      $('#idope').html(' ');
      $('#idope').append('<option value="0">Selecciona un Operador</option>');
      $('#iduni').html(' ');
      $('#iduni').append('<option value="0">Selecciona un Unidad</option>');
      $('#iddes').html(' ');
      $('#iddes').append('<option value="0">Selecciona un Destino</option>');

      hoy();
      $('#fechainicio').val(hoy);
      $('#fechafinal').val(hoy);
      $("#fechainicio").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
      $("#fechafinal").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
                  
                  /// LISTAR CLIENTES
                      $.ajax({
                        url: 'ajax.php?c=OrdenServicio&f=listaClientes',  
                        type: 'POST',
                        dataType: 'json',
                      })
                        .done(function(data) {
                          //alert(idcliente);
                            $.each(data, function(index, val) {
                                $('#idclie').append('<option value="'+val.id+'">'+val.nombretienda+'</option>');  
                            });
                            $("#divreporteador").show();
                       })
                  /// LISTAR CAPACIDADES
                      $.ajax({
                        url: 'ajax.php?c=OrdenServicio&f=listaCapacidad',
                        type: 'POST',
                        dataType: 'json',
                      })
                        .done(function(data) {
                            $.each(data, function(index, val) {
                                $('#idcap').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');  
                            });
                        })
                    ///LISTAR OPERADORES
                        $.ajax({
                            url: 'ajax.php?c=pantallaelectronica&f=listaOperadores',
                            type: 'POST',
                            dataType: 'json',
                        })
                          .done(function(data) {
                                $.each(data, function(index, val) {
                                    $('#idope').append('<option value="'+val.idEmpleado+'">'+val.operador+'</option>');   
                                });
                          })
                     ///LISTAR UNIDADES
                          $.ajax({
                            url: 'ajax.php?c=pantallaelectronica&f=listaUnidades',
                            type: 'POST',
                            dataType: 'json',
                          })
                            .done(function(data) {
                                $.each(data, function(index, val) {
                                    $('#iduni').append('<option value="'+val.idunidad+'">'+val.no_economico+'</option>');   
                                });
                            })
                      ///LISTAR DESTINOS
                          $.ajax({
                            url: 'ajax.php?c=pantallaelectronica&f=listaDestinos',
                            type: 'POST',
                            dataType: 'json',
                          })
                            .done(function(data) {
                                $.each(data, function(index, val) {
                                    $('#iddes').append('<option value="'+val.iddatosentrega+'">'+val.destino+'</option>');   
                                });
                            })

                    //$("#divreporteador").show();
    }
    function buscar(){
      
      fechainicio = $("#fechainicio").val();
      fechafinal = $("#fechafinal").val();
      idcliente = $("#idclie").val();
      idoperador = $("#idope").val();
      iddestino = $("#iddes").val();
      idunidad = $("#iduni").val();
      idcapacidad = $("#idcap").val();
   

      $.ajax({
        data: {fechainicio:fechainicio,fechafinal:fechafinal,idcliente:idcliente,idoperador:idoperador,iddestino:iddestino,idunidad:idunidad,idcapacidad:idcapacidad},
        url: 'ajax.php?c=pantallaelectronica&f=listaPantallaCH',
        type: 'POST',
        dataType: 'json',
      })
        .done(function(data) {
                          //console.log(data); 
                        var table = $('#table_listaHistoria').DataTable();
                            //$('.filas').empty();
                            table.clear().draw();
                            var x ='';
                            var btnreporte = '';

                            $.each(data, function(index, val) {
                              btnreporte = '<a class="btn btn-sm btn-success" title="Ver Reportes" onclick="reportes('+val.idordenservicio+', \''+val.operador+'\', \''+val.cliente+'\',\''+destino+'\')"><i class="glyphicon glyphicon-list"></i></a>';
                                x ='<tr idordenservicio="'+idordenservicio+'" class="filas" id="fila'+idordenservicio+'">'+
                                                '<td>'+val.asignacion+'</td>'+
                                                '<td>'+val.idordenservicio+'</td>'+
                                                '<td>'+val.cliente+'</td>'+
                                                '<td>'+val.operador+'</td>'+
                                                '<td>'+val.origen+'</td>'+
                                                '<td>'+val.destino+'</td>'+
                                                '<td>'+btnreporte+'</td>'+
                                                //'<td><a class="btn btn-sm btn-default" title="Gasto" onclick="addcol('+idordenservicio+')"><i class="glyphicon glyphicon-road"></i></a></td>'+
                                                '</tr>';  
                                    table.row.add($(x)).draw();   

                            });
              $("#tablaHistoria").show();

                    }) 
      
    }   

      var patron = new Array(2,2)
      function mascara(d,sep,pat,nums){
          if(d.valant != d.value){
            val = d.value
            largo = val.length
            val = val.split(sep)
            val2 = ''
            for(r=0;r<val.length;r++){
              val2 += val[r]  
            }
            if(nums){
              for(z=0;z<val2.length;z++){
                if(isNaN(val2.charAt(z))){
                  letra = new RegExp(val2.charAt(z),"g")
                  val2 = val2.replace(letra,"")
                }
              }
            }
            val = ''
            val3 = new Array()
            for(s=0; s<pat.length; s++){
              val3[s] = val2.substring(0,pat[s])
              val2 = val2.substr(pat[s])
            }
            for(q=0;q<val3.length; q++){
              if(q ==0){
                val = val3[q]
              }
              else{
                if(val3[q] != ""){
                  val += sep + val3[q]
                  }
              }
            }
            d.value = val
            d.valant = val
          }
    }

    function reloadtable(estatus){
      idordenservicio = "";
        $.ajax({
          data: {estatus:estatus},
          url: 'ajax.php?c=pantallaelectronica&f=listaPantalla',
          type: 'POST',
          dataType: 'json',
        })
                    .done(function(data) {
                          //console.log(data); 
                        var table = $('#table_listaPantalla').DataTable();
                            //$('.filas').empty();
                            //table.clear().draw();
                            var x ='';
                            var idordenservicio='', operador='', destino='', cliente='', solicitud='', asignacion='', btnreporte ='', estatus ='';
                            $.each(data, function(index, val) {
              
                              if(val.operador == null){
                                operador = "";
                                btnreporte = "";
                              }
                              else{
                                operador=val.operador;
                              }
                              if(val.destino == null){
                                destino = "";
                              }
                              else{
                                destino=val.destino;
                              }
                              if(val.cliente == null){
                                cliente = "";
                              }
                              else{
                                cliente=val.cliente;
                              }
                              if(val.solicitud == null){
                                solicitud = "";
                              }
                              else{
                                solicitud=val.solicitud;
                              }
                              if(val.asignacion == null){
                                asignacion = "";
                              }
                              else{
                                asignacion=val.asignacion;
                              }

                              if(val.idordenservicio == null){
                                idordenservicio = "";
                              }
                              else{
                                idordenservicio=val.idordenservicio;
                                btnreporte = '<a class="btn btn-sm btn-success" title="Reporte" onclick="reporte('+val.idordenservicio+', \''+val.operador+'\', \''+val.cliente+'\',\''+destino+'\')"><i class="glyphicon glyphicon-road"></i></a><a class="btn btn-sm btn-info" title="Ver Reportes" onclick="reportes('+val.idordenservicio+', \''+val.operador+'\', \''+val.cliente+'\',\''+destino+'\')"><i class="glyphicon glyphicon-list"></i></a>';
                                 
                              }

                              if( val.estatus == '1'){
                                asignacion = "";
                                idordenservicio = "";
                                solicitud = "";
                                cliente = "";
                                destino = "";
                                operador = "";
                                btnreporte = "";
                              }
                               var REPORTE = '';
                            if(val.reporte >= 1){
                              REPORTE = 'CON REPORTE';
                            }
                            else{
                              REPORTE = 'SIN REPORTE';
                            }

                                x ='<tr idordenservicio="'+idordenservicio+'" class="filas" id="fila'+idordenservicio+'">'+
                                                '<td>'+val.no_economico+'</td>'+
                                                '<td>'+val.capacidad+'</td>'+
                                                '<td>'+val.estatus2+' / '+REPORTE+'</td>'+
                                                '<td>'+idordenservicio+'</td>'+
                                                '<td>'+operador+'</td>'+
                                                '<td>'+destino+'</td>'+
                                                '<td>'+cliente+'</td>'+
                                                '<td>'+solicitud+'</td>'+
                                                '<td>'+asignacion+'</td>'+
                                                '<td>'+btnreporte+'</td>'+
                                                //'<td><a class="btn btn-sm btn-default" title="Gasto" onclick="addcol('+idordenservicio+')"><i class="glyphicon glyphicon-road"></i></a></td>'+
                                                '</tr>';  
                                    table.row.add($(x)).draw();   

                                $.ajax({
                                  data: {idordenservicio:idordenservicio},
                                  url: 'ajax.php?c=pantallaelectronica&f=listaReportesEsp',
                                  type: 'POST',
                                  dataType: 'json',
                                })
                                .done(function(data) {
                                  $.each(data, function(index, val) {
                                    fecha = val.fecha;
                                    hora = val.hora;
                                    idordenservicio =  val.idordenservicio; 


                                    addcol(idordenservicio,fecha,hora);
                                  console.log(data);
                                   });
                                })

                            });

                    
    $("#tablaPantalla").show();
                      }) 
    }
    /*function addcol(idordenservicio,fecha,hora){

      $("#fila"+idordenservicio).append( "<td >Fecha:"+fecha+"<br> Hora:"+hora+"</td>" );
    }
*/
    function reportes(idordenservicio,operador,clinete,destino){

      $("#idordenservicio").val(idordenservicio).prop('readonly',true);
      $("#operador").val(operador).prop('readonly',true);
      $("#destino").val(destino).prop('readonly',true);

      $("#modal_form_reportes").modal('show');

                              $.ajax({
                                  data: {idordenservicio:idordenservicio},
                                  url: 'ajax.php?c=pantallaelectronica&f=listaReportesEsp',
                                  type: 'POST',
                                  dataType: 'json',
                                })
                               .done(function(data) {
                          //console.log(data); 
                        var table = $('#table_listaReportes').DataTable({
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
              
                                x ='<tr idordenservicio="'+idordenservicio+'" class="filas">'+
                                                '<td>'+val.num_reportes+'</td>'+
                                                '<td>'+val.idordenservicio+'</td>'+
                                                '<td>'+val.operador+'</td>'+
                                                '<td>'+val.destino+'</td>'+
                                                '<td>'+val.fecha+'</td>'+
                                                '<td>'+val.hora+'</td>'+
                                                '<td>'+val.km+'</td>'+
                                                '<td>'+val.ubicacion+'</td>'+
                                                '<td>'+val.observaciones+'</td>'+
                                                //'<td><a class="btn btn-sm btn-default" title="Gasto" onclick="addcol('+idordenservicio+')"><i class="glyphicon glyphicon-road"></i></a></td>'+
                                                '</tr>';  
                                    table.row.add($(x)).draw();   
                            });
                    }) 
    }

  function reporte(idordenservicio,operador,cliente,destino){

    function hora(){
      var date = new Date;
      //var seconds = date.getSeconds();
      var minutes = date.getMinutes();
      if (minutes<10){ // para agregar el 0 
        minutes = "0"+minutes;
      }
      var hour = date.getHours();
      var hora = (hour+":"+minutes);
      return hora; 
    }

    hora();
    hoy();
    var estatus = "VIAJE";
    $('#estatus').prop('checked', false);
    $("#idordenservicio1").val(idordenservicio).prop('readonly', true);
    $("#operador1").val(operador).prop('readonly', true);
    $("#cliente1").val(cliente).prop('readonly', true);
    $("#destino1").val(destino).prop('readonly', true);

    $("#fecha").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
    $("#fecha").val(hoy);
    
    $('#hora').val(hora);
    $('#estatus').val(estatus);   
    $("#modal_form_reporte").modal('show');


    $('#estatus').change(function() {
        if ($(this).is(':checked')) {
          estatus = "FINALIZADO";
          $('#estatus').val(estatus);
        } else {
          estatus = "VIAJE";
          $('#estatus').val(estatus);
        }
      });

    }

    function add_reporte(){
        idordenservicio = $("#idordenservicio1").val();
        operador = $("#operador1").val();
        cliente = $("#cliente1").val();
        destino = $("#destino1").val();
        fecha = $("#fecha").val();
        hora = $("#hora").val();
        km = $("#km").val();
        ubicacion = $("#ubicacion").val();
        observ = $("#observ").val();
        estatus = $("#estatus").val();
        //num_reporte = 0;
        //idEmpleado = 0;


        $.ajax({
          data: {idordenservicio:idordenservicio},
          url: 'ajax.php?c=pantallaelectronica&f=listaReportesMax',
          type: 'POST',
          dataType: 'json',
        })
                      .done(function(data){
                        num_reporte = 0;
                        idEmpleado = 0;
                        idunidad = 0;
                        idcajatractor = 0;
 
                          console.log(data);
                          if(data == null){
                            num_reporte = 1;
                            idEmpleado = (val.idEmpleado * 1);
                            idunidad = (val.idunidad * 1);
                            idcajatractor = (val.idcajatractor * 1);
                          }else{
                              $.each(data, function(index, val) {
                                num_reporte = (val.num_reporte * 1) + 1;
                                idEmpleado = (val.idEmpleado * 1);
                                idunidad = (val.idunidad * 1);
                                idcajatractor = (val.idcajatractor * 1);
                              });
                          }
                         
                            $.ajax({
                            data: {idordenservicio:idordenservicio,num_reporte:num_reporte,fecha:fecha,hora:hora,km:km,ubicacion:ubicacion,observ:observ,estatus:estatus,idEmpleado:idEmpleado,idunidad:idunidad,idcajatractor:idcajatractor},
                            url: 'ajax.php?c=pantallaelectronica&f=saveReporte',
                            type: 'POST',
                            dataType: 'text',
                          })
                           .done(function(data){
                         pantallaElectronica();
                         $("#modal_form_reporte").modal('hide');
                           })
                      })



         

    }
          
/// 