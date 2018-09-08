$(document).ready(function(){
	
		  $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	
	 $("#fechainicio").datepicker({
	 	maxDate: 365,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#final").datepicker("option","minDate", selected);
        }
    });
    $("#fechafin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:365,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#inicial").datepicker("option","maxDate", selected);
        }
    });
    
    
    // $('#table').DataTable({
	// language: {
	    // search: "Buscar Retencion:",
	    // lengthMenu:"Mostrar _MENU_ Retencion",
	    // zeroRecords: "No hay Retenciones.",
	    // infoEmpty: "No hay Retencion que mostrar.",
	    // info:"Mostrando del _START_ al _END_ de _TOTAL_ Retencion",
        // infoFiltered: "( _TOTAL_ Retencion Encontradas)",
	    // paginate: {
	        // first:      "Primero",
	        // previous:   "Anterior",
	        // next:       "Siguiente",
	        // last:       "Último"
	        // }
	 // }
// });
    
 });
 $(function() {
   
$('#load').on('click', function() { 
   
   $(this).button('loading');
   
   if( !$("#fechafin").val() || !$("#fechainicio").val()){
   	alert("Seleccione la fecha");
   	$(this).button('reset');
   }
   else{
   	$("#formfecha").submit();
   }
   
 });
  });
 
 function abreImpuestos(idRetencion){
	$("#impuestos").show();
	 $("#impuestos").dialog(
	 {
			 autoOpen: false,
			 width: 900,
			 height: 310,
			 modal: true,
			 show:
			 {
				effect: "clip",
				duration: 500
			 },
				hide:
			 {
				effect: "clip",
				duration: 500
			 },
			 buttons: 
			{
				"Cerrar": function (){
					
					$('#impuestos').dialog('close');		
				}
			}
		});

	$('#impuestos').dialog({position:['center',200]});
	$('#impuestos').dialog('open');	
	$('#impuestos').load('ajax.php?c=Cheques&f=verImpuestosFactura&id='+idRetencion);
       

 }

function mailBanco(xml){
	var msg = "Registre el correo electrónico a quién desea enviarle el XML:";
	var a = prompt(msg,"@netwarmonitor.com");
	if(a!=null){
		$("#loading").fadeIn(500);
		$("#divmsg").load("mail.php?a="+a, {xml:xml});
	}
}
function volverTimbrar(trackID,idretencion,idPrv){
   
   $('#timbre'+trackID).button('loading');
   	$.post("ajax.php?c=Cheques&f=volverAtimbrar",
	{trackID:trackID,
	idretencion:idretencion,
	idPrv:idPrv},
	function(resp){
		 $('#timbre'+trackID).button('reset');
		alert(resp);
  		$("#load").click();
	});
}
function cancelarFactura(uuid,idcomprobante){
   
   if(confirm("Esta seguro de cancelar esta retencion?")){
   		$("#cargador"+idcomprobante).show();
   		$("#timbrenormal"+idcomprobante).hide();
   		$.post("ajax.php?c=Cheques&f=cancelaFactura",
		{uuid:uuid,
		idcomprobante:idcomprobante},
		function(resp){
			alert(resp);
			$("#cargador"+idcomprobante).hide();
			$("#timbrenormal"+idcomprobante).show();
			$("#load").click();
		});
   }
   	
}
	