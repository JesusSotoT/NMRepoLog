$(document).ready(function(){
$('#table').DataTable({
	
	language: {
		search: "Buscar Anticipo:",
	    lengthMenu:"Mostrar _MENU_ Anticipos",
	    zeroRecords: "No hay Anticipos.",
	    infoEmpty: "No hay Anticipos que mostrar.",
	    info:"Mostrando del _START_ al _END_ de _TOTAL_ Anticipos",
        infoFiltered: "( _TOTAL_ Anticipos Encontrados)",
	    paginate: {
	        first:      "Primero",
	        previous:   "Anterior",
	        next:       "Siguiente",
	        last:       "Último"
	        }
	}
	 
});
});
function facturas(idpoliza,idnodeducible){
$("#nodeducible").val(idnodeducible);
$("#idpoli").val(idpoliza);
$("#Anexa").modal('show');	
$("#factura").val("");
verFactNodeducible();	
}
$(function()
 {
$( '#fac' )
  .submit( function( e ) { 
  	$('#verif').css('display','inline');
    $.ajax( {
      url: 'ajax.php?c=CaptPolizas&f=subeComproNodeducible',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } ).done(function( request1 ) {
    	
    		if(request1 == 1){
    			alert("Factura anexada a Ticket");
    			verFactNodeducible();
    			window.location="index.php?c=Captpolizas&f=viewComprobanteTicketAnticipo";
    		}else if(request1 == 0){
    			alert("No se pudo anexar factura intenta de nuevo");
    		}else{
    			alert(request1);
    		}
    		
			
			$('#verif').css('display','none');
			
    
  	});
    e.preventDefault();
  });
});
function verFactNodeducible(){
	$('#listaFacturas').html("");
	$.post("ajax.php?c=CaptPolizas&f=facturasNodeducible",
	 	{
			idpoliza: $('#idpoli').val(),
			idNodeducible: $('#nodeducible').val()
		},
		function(request)
		{
			
		 	$('#listaFacturas').html(request);
			
		});
}
function eliminar(archivo){
	$archivosepara = archivo.split("/");
 	var confirmacion = confirm("Esta seguro de eliminar este archivo: \n"+$archivosepara[5]);
 		if(confirmacion)
 		{
 			$.post("ajax.php?c=CaptPolizas&f=EliminafacturasNodeducible",
		 	{
				idpoliza: $('#idpoli').val(),
				idNodeducible: $('#nodeducible').val(),
				archivo: archivo
			},
			function(request)
			{
				if(request == 1){
					alert("Factura Eliminada");
			 		$('#listaFacturas').html("");
			 		window.location="index.php?c=Captpolizas&f=viewComprobanteTicketAnticipo";

				}else{
					alert("No se pudo eliminar la factura Intenta de nuevo");
				}
			});
		}
 		
 }
