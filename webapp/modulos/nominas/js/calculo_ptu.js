var datosCalculoPTU;

$(document).ready(function() {

    var table = $('#tablaptu')
    .DataTable(
    {
        "language": {
            "url": "js/Spanish.json"
        },
        "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

// converting to interger to find total
var intVal = function ( i ) {
    return typeof i === 'string' ?
    i.replace(/[\$,]/g, '')*1 :
    typeof i === 'number' ?
    i : 0;
};

// computing column Total of the complete result 
var monTotal = api
.column(4)
.data()
.reduce( function (a, b) {
    return intVal(a) + intVal(b);
}, 0 );

var wedTotal = api
.column(6)
.data()
.reduce( function (a, b) {
    return intVal(a) + intVal(b);
}, 0 );

// Update footer by showing the total with the reference of the column index 
$( api.column(4).footer()).html(numeral(monTotal).format('0,0.00'));
$( api.column(6).footer()).html(numeral(wedTotal).format('$0,0.00'));
},
"fnInitComplete": function(){
// Disable TBODY scoll bars
$('.dataTables_scrollBody').css({
    'overflow': 'hidden',
    'border': '0'
});

// Enable TFOOT scoll bars
$('.dataTables_scrollFoot').css('overflow', 'auto');

// Sync TFOOT scrolling with TBODY
$('.dataTables_scrollFoot').on('scroll', function () {
    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
});                    
},
"scrollX": true,
"colResize": {
    "tableWidthFixed": false
},
});

    datosCalculoPTU ='';
    $('#agregarconce').css({'display':'none'}); 
} );




$(function() {
    $(".numbersOnly").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    fn_dar_eliminar();
    fn_cantidad();
//$("#frm_usu").validate();
$('#idnomp').on('change', function(){
    var disabled = $(this).val() == 'true' ? false : true;
});


$('#nombre').on('change', function(){

    valip = $(this).val(); 
    $.ajax({
        url:"ajax.php?c=Sobrerecibo&f=periodo",
        type: 'POST',
        dataType:'json',
        data:{idtipo: $(this).val() },
        success: function(r){
            if(valip=='*'){
                option='<option value="*">Todos</option>';
            }else{
                option='';
            }
            if(r.success==1 ){

                option='<option value="*">Todos</option>';
                $.each(r.data, function( k, v ) {  
                    option+='<option value="'+v.idtipo+'">('+v.descripcion+')</option>';
                });
            }else{
                option+='<option value="">Vacio</option>';         
            }
            $('#nominas').html(option);
            $('#nominas').selectpicker('refresh');
        }
    });


});
$('#load').on('click', function(evt) { 
    if($("#montoRepartir").val().trim() =='' || isNaN($("#montoRepartir").val())){             
        alert("Campo obligatorio.");
        evt.preventDefault();
        $(this).button('reset'); 

    }
});


$('#guardarPTU').on('click', function(evt) { 
    if($("#montoRepartir").val().trim() =='' || isNaN($("#montoRepartir").val())){             
        alert("Campo obligatorio.");
        evt.preventDefault();
        $(this).button('reset'); 

    }else{

        $.ajax({
            url:"ajax.php?c=Sobrerecibo&f=existePTU",
            type: 'POST',
            dataType:'json',
            success: function(r){
                var existeptu = r[0].existeptu;  
                var prenominaautorizados = r[0].prenominaautorizados;

                if (existeptu ==0){
                    guardaPTU();
                }
                else {

                    if(confirm("Ya tiene un cálculo de PTU del ejercicio actual,¿Desea reemplazarlo?")){
                        if (prenominaautorizados >0){
                            alert ("Existen recibos PTU del ejercicio autorizados, no puede reemplazar la informacion."); 
                        }
                        else
                            guardaPTU();
                    } 
                }
            },
            error: function(r){
                alert(r);
            }
        });
    }
    return 0;
});

guardaPTU = function(){
    $.ajax({
        url:"ajax.php?c=Sobrerecibo&f=guardarPTU",
        type: 'POST',
        dataType:'json',
        data:
        {   
            montoRepartir: $("#montoRepartir").val(),
            descontarincidencias:  $("#descontarincidencias").val(),
            ejercicio:$("#ejercicio").val(),
            ptu:$("#ptu").val()

        },
        success: function(r){
//alert(r);
if(r==1){
    alert("Guardado.");
}else if (r=2) {
    alert("No tiene registrado ningun periodo Extraordinario.");

}

else{
    alert("Error.");
} 
},
error: function(e){
    alert("Error.");
}
});
}

$('#ejercicio').on('change',function(event) {
    
    $.ajax({
        url:"ajax.php?c=Sobrerecibo&f=obtenAcumulado",
        type: 'POST',
        dataType:'json', 
        success: function(r){
            var valor = $("#ejercicio").val();
            if(valor==1){
                if ($("#montoRepartir").val()!='') {
                    var monto = $("#montoRepartir").val();
                    var total = Number(monto)+Number(r);
                    $('#montoRepartir').val(total);
                } 
            }
            if(valor==2){
                if ($("#montoRepartir").val()!='') {
                    var monto = $("#montoRepartir").val();
                    var total = Number(monto)-Number(r);
                    $('#montoRepartir').val(total);
                } 
            }  
        },
        error: function(e){ 
            //alert("error");
        }
    });
});
});


function fn_cantidad(){
    cantidad = $("#grilla tbody").find("tr").length;
    $("#span_cantidad").html(cantidad);
};

function fn_agregar(){
    if ($("#nombre").val()!='*' &&  $("#nominas").val()!='*') {
//alert("Entro");
cadena = "<tr>";
cadena = cadena + "<td>" + $("#nombre>option:selected").text() + "</td>";
cadena = cadena + "<td>" + $("#nominas>option:selected").text() + "</td>";
cadena = cadena + "<td><a class='elimina'><img src='images/borro.png' style='width: 22px;height: 20px;' /></a></td>";
$("#grilla tbody").append(cadena);
/*
aqui puedes enviar un conunto de tados ajax para agregar al usuairo
$.post("agregar.php", {ide_usu: $("#valor_ide").val(), nom_usu: $("#valor_uno").val()});
*/
fn_dar_eliminar();
fn_cantidad();
alert("Concepto agregado");
}
};

function fn_dar_eliminar(){
    $("a.elimina").click(function(){
        id = $(this).parents("tr").find("td").eq(0).html();
        respuesta = confirm("Desea eliminar el concepto: " + id);
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                alert("Concepto " + id + " eliminado")
/*
aqui puedes enviar un conjunto de datos por ajax
$.post("eliminar.php", {ide_usu: id})
*/
})
        }
    });
};
