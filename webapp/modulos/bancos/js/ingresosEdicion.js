/**
 * @author Carmen Gutierrez
 */
var contenido ="";
$(document).ready(function(){
$('#table').DataTable({
	dom: 'Bfrtip',
    buttons: [ 'excel' ],
	language: {
		search: "Buscar Documento:",
	    lengthMenu:"Mostrar _MENU_ Documentos",
	    zeroRecords: "No hay Documentos.",
	    infoEmpty: "No hay documentos que mostrar.",
	    info:"Mostrando del _START_ al _END_ de _TOTAL_ Documentos",
        infoFiltered: "( _TOTAL_ Documentos Encontrados)",
	    paginate: {
	        first:      "Primero",
	        previous:   "Anterior",
	        next:       "Siguiente",
	        last:       "Último"
	        }
	}
	 
});

contenido = $("#nodepo > tbody").html();

   
    
    $("#buscar").keyup(function(){
		if( $(this).val() != "")
		{
			$("#nodepo tbody>tr").hide();
			$("#nodepo td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			$("#nodepo tbody>tr").show();
		}
	});
	
	
	$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});
 
});

