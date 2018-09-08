function cambiarPestana(pestannas,pestanna) {
    
    pestanna = document.getElementById(pestanna.id);
    listaPestannas = document.getElementById(pestannas.id);
    cpestanna = document.getElementById('c'+pestanna.id);
    listacPestannas = document.getElementById('contenido'+pestannas.id);
    
    i=0;
     while (typeof listacPestannas.getElementsByTagName('div')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByTagName('div')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
        });
        i += 1;
    }
 
    $(document).ready(function(){
         $(cpestanna).css('display','');
        $(pestanna).css('background','dimgray');
        $(pestanna).css('padding-bottom','2px');
    });
 
}
function excelreport(){
var url = "ajax.php?c=declaracionr54&f=ejecutareporte&excel=1&ejercicio="+$('#ejercicio').val()+"&delperiodo="+$('#delperiodo').val()+"&inversiones="+$('#inversiones').val()+"&inventarios="+$('#inventarios').val()+"&inmediataperdida="+$('#inmediataperdida').val()+"&perdidas="+$('#perdidas').val()+"&enajenacion="+$('#enajenacion').val()+"&ISRautorizadas="+$('#ISRautorizadas').val()+"&ISRentregados="+$('#ISRentregados').val()+"&ISRretenido="+$('#ISRretenido').val()+"&maquiladoras="+$('#maquiladoras').val()+"&proviIETU="+$('#proviIETU').val()+"&cargo="+$('#cargo').val()+"&favor="+$('#favor').val()+"&cargo="+$('#cargo').val()+"&estimuloactual="+$('#estimuloactual').val();
window.open(url, '_blank');
}
function excelreport2(){
var url = "ajax.php?c=declaracionr54&f=ejecutareporte&excel=1&ejercicio="+$('#ejercicio').val()+"&delperiodo="+$('#delperiodo').val()+"&inversiones="+$('#inversiones').val()+"&inventarios="+$('#inventarios').val()+"&inmediataperdida="+$('#inmediataperdida').val()+"&perdidas="+$('#perdidas').val()+"&enajenacion="+$('#enajenacion').val()+"&ISRautorizadas="+$('#ISRautorizadas').val()+"&ISRentregados="+$('#ISRentregados').val()+"&ISRretenido="+$('#ISRretenido').val()+"&maquiladoras="+$('#maquiladoras').val()+"&proviIETU="+$('#proviIETU').val()+"&cargo="+$('#cargo').val()+"&favor="+$('#favor').val()+"&cargo="+$('#cargo').val()+"&estimuloactual="+$('#estimuloactual').val()+"&estimuloanterior="+$('#estimuloanterior').val()+"&detalle="+$('#detalle').val();
window.open(url, '_blank');
}
