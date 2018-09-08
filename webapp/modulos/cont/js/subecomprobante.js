/**
 * @author Carmen Gutierrez
 */
function facturas(idanticipo){
	$("#plz").val(idanticipo);
	$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
 	{
		IdPoliza: idanticipo
	 },
	 function(data)
	 {
	 	$('#listaFacturas tbody').html(data);
	 	calcula();
	 });
	 
	 $("#Facturas").dialog(
	 {
			 autoOpen: false,
			 width: 700,
			 height: 510,
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
				"Cerrar": function () 
				{
				 $("#Facturas").dialog('close');
				}
			}
		});

	$('#Facturas').dialog({position:['center',200]});
	$('#Facturas').dialog('open');	

}

// function actualizaListaFac(idpoliza)
// {
// $.post("ajax.php?c=CaptPolizas&f=listaFacturas",
		 	// {
				// IdPoliza:idpoliza,
			 // },
			 // function(data)
			 // {
// 				
			 	// $('#facturaSelect').html(data)
// 				
			 // });
// }
$(function()
 {
$( '#fac' )
  .submit( function( e ) {
  	$('#verif').css('display','inline');
    $.ajax( {
      url: 'ajax.php?c=CaptPolizas&f=subeFactura',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } ).done(function( data1 ) {
    
    	//$("#Facturas").dialog('refresh')
    		$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
		 	{
				IdPoliza: $('#plz').val()
			},
			function(data2)
			{
				var recalantes = parseFloat( $("#"+$('#plz').val()).html() ) + parseFloat($("#totalfinal").html());
				$("#"+$('#plz').val()).html(recalantes.toFixed(2));
			 	$('#listaFacturas tbody').html(data2);
				calcula();
				var recaldespues = parseFloat( $("#"+$('#plz').val()).html() ) - parseFloat($("#totalfinal").html());
				$("#"+$('#plz').val()).html(recaldespues.toFixed(2));
					
			});
			$('#factura').val('');
			//actualizaListaFac();
			data1 = data1.split('-/-*');
			$('#verif').css('display','none');
			if(parseInt(data1[2]))
			{
				alert('Los siguientes archivos no son validos: \n'+data1[3]);
			}

			if(parseInt(data1[0]))
			{
				alert('Archivos Validados: \n'+data1[1]);
			}
    
  	});
    e.preventDefault();
  } );
  
   });
 // function agregadeducible2(){
	// $("#datos tfoot tr:eq(0)").clone().removeClass('fila_base').appendTo("#datos tfoot");
// }
// 
// $(document).on("click",".eliminar",function(){
		// var parent = $(this).parents().get(0);
		// $(parent).remove();
	// }); 
	function agregadeducible(idanticipo,anticipo){
		$("#anticipode").empty();
		
 		$("#userdeducible").dialog(
	 	{
			 autoOpen: false,
			 width: 400,
			 height: 410,
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
				"Guardar": function () 
				{
				 $("#envia").click();
				}
			}
		});

	$('#userdeducible').dialog({position:['center',200]});
	$('#userdeducible').dialog('open');	
	$.post('ajax.php?c=CaptPolizas&f=muestraDeducible',{
		idanticipo:idanticipo
	},function(resp){
		$("#anticipode").html(anticipo);
		$("#idanticipo").val(idanticipo);
		
		$("#tabla tfoot").html(resp);
		calculaNoDeducible();
		
		
	});
	}

function calculaNoDeducible(){
	var total=0;
    $('input[name="importe[]"]').each(function() { 
    		total += parseFloat($(this).val() || 0) ; 
    });
      $("#totalnodedu").html(total.toFixed(2));     // break;
                   
              
} 
function calcula(){
         var total=0;
        $("#listaFacturas tbody tr").each(function (index) 
        {
            $(this).children("td").each(function (index2) 
            {
                switch (index2) //indice
                {
                    case 6:
                    if($(this).text().replace(/,/gi,'')>0){
                    		total += parseFloat($(this).text().replace(/,/gi,''));
                    }
                     
                    break;
                   
                }
            });
            
        });
        $("#totalfinal").html(total.toFixed(2));
        
  }
  
	

