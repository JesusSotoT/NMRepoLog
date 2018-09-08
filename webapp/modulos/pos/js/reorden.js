
function hoy2(){
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1; //hoy es 0!
    var yyyy = hoy.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 
    if(mm<10) {
        mm='0'+mm
    } 

    return hoy = yyyy+'-'+mm+'-'+dd;
}
function mesA(){
    var fecha=new Date();
    var mesA=new Date(fecha.getTime() - (24*60*60*1000)*30);
    var dd = mesA.getDate();
    var mm = mesA.getMonth()+1;
    var yyyy = mesA.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 
    if(mm<10) {
        mm='0'+mm
    } 
    return mesA = yyyy+'-'+mm+'-'+dd;
}
$('#tipo').change(function(){   
         var tipo = $("#tipo").val();     
         $.ajax({ 
                        data : {tipo:tipo},
                        url: 'ajax.php?c=reorden&f=productos',
                        type: 'post',
                        dataType: 'json',
                    })
                    .done(function(data) {
                        console.log(data);
                        $('#producto').empty();
                        $('#producto').select2("val", '');
                        $('#producto').append('<option value="0">-Todos-</option>');
                        $.each(data, function(index, val) {
                              $('#producto').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                        });                       
                    }) 
     });
function generar(){
    var tipo        = $("#tipo").val();
    var producto    = $("#producto").val();
    var suc         = $("#suc").val();
    var dias        = $("#dias").val();
    var desde       = $("#desde").val();
    var hasta       = $("#hasta").val();

    if(dias <= 0 || dias == ''){
        alert('Los dias deben ser mayor que cero');
        return false;
    }

    if(desde > hasta || desde == '' || hasta == ''){
        alert('Debe Selecionar un Rango Correcto');
        return false;
    }

    $("#divtable").empty();
    $.ajax({
            url: 'ajax.php?c=reorden&f=generar',
            type: 'post',
            data:{tipo:tipo,producto:producto,suc:suc,dias:dias,desde:desde,hasta:hasta}
    })
    .done(function(data) {
        $("#divtable").append(data);
        $('#tableReorden').DataTable({dom: 'Bfrtip',
                                                            buttons: [  
                                                                        {
                                                                            extend: 'print'                                    
                                                                        },
                                                                        'excel',
                                                                    ],
                                                            language: { 
                                                                buttons: {
                                                                    print: 'Imprimir'
                                                                }
                                                            },
                                                            columnDefs: [
                                                                {
                                                                    targets: [ 7 ],
                                                                    visible: false,
                                                                    searchable: true
                                                                },
                                                                {
                                                                    targets: [ 8 ],
                                                                    visible: false,
                                                                    searchable: true
                                                                }
                                                            ],
                                                            destroy: true,
                                                            searching: true,
                                                            paginate: false,
                                                            filter: true,
                                                            sort: false,
                                                            info: true,
                                                            language: { 
                                                                buttons: {
                                                                    print: 'Imprimir'
                                                                },                                                                   
                                                                search: "Buscar:",
                                                                lengthMenu:"Mostrar _MENU_ elementos",
                                                                zeroRecords: "No hay datos.",
                                                                infoEmpty: "No hay datos que mostrar.",
                                                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                                                paginate: {
                                                                            first:      "Primero",
                                                                            previous:   "Anterior",
                                                                            next:       "Siguiente",
                                                                            last:       "Último"
                                                                }
                                                            },
                                    });
    })
}