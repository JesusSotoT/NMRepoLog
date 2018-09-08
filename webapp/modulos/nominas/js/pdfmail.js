	
	//I N I C I A   G E N E R A   P D F 
 	function pdf(){
        $('.trsize').removeAttr( 'style' );


	    $('.accOculta').hide();
	    $('.tablasobrerecibo').css({'fontSize':'9px'});
	    $('.iEncab').css({'background-color':'rgb(48,73,95)','height':'20px','color':'while'});  
		$('.brmail').css({'display':'none'});
		$('.agregPD').css({'display':'none'});

		 var contenido_html = $("#imprimible").html();

		 $("#contenido").text(contenido_html);
		 $('.agregPD').css({'display':'inline'});
		 $("#divpanelpdf").modal('show');
		 $('.tablasobrerecibo').css({'fontSize':'12.5px'});
		 $(".color").css({'background-color':'while'});
		 $('.editbls').removeClass('editbls') 
		 $('.accOculta').show();
		 $('.editbls').addClass('editbls')	;
		   $('.trsize').css( 'style' );
		    $('.trsize').css({'fontSize':'12px'});
	}
	function generar_pdf(){
		
		$("#divpanelpdf").modal('hide');
	}
	function cancelar_pdf(){
		$("#divpanelpdf").modal('hide');	
	}

	function pdf_generado(){
		
		alert("OK");
		
	}	
	// TERMINA GENERA PDF
	

	// C O M I E N Z A   G E N E R A R   M A I L 
	function mail(){
		
		var msg = "Registre el correo electrónico a quién desea enviarle el reporte:";
		var a = prompt(msg,"@netwarmonitor.com");
		if(a!=null){
			$('.accOculta').hide();
		 	$('.iEncab').css({'background-color':'rgb(48,73,95)','height':'20px','color':'while'});  
		    $('.agregPD').css({'display':'none'});
		    $('.estinegrit').css({'fontWeight':'bold'});
			var html_contenido_reporte;
			html_contenido_reporte = $("#imprimible").html();
			
			$("#loading").fadeIn(500);
			$("#divmsg").load("../../../webapp/netwarelog/repolog/mail.php?a="+a, {reporte:html_contenido_reporte});
			$('.accOculta').show();
			$('.agregPD').css({'display':'inline'});
		}
	}	
	// TERMINA GENERAR MAIL
	
	// C O M I E N Z A   I M P R I M I R 
	function printl(){
		 $('.accOculta').css({'display':'none'});

//$('.uno').removeAttr( 'style' );
// $('.uno').removeAttr( 'style' );




		// $('.tabpercdedu').removeClass('table'); 
		// $('.tabpercdedu').removeClass('titcolor'); 
		
		//$('.accOculta').hide();  
     
		setTimeout(function () { 
			//$('.uno').Attr( 'style' );

         // $('.tabpercdedu').addClass('table');
         // $('.tabpercdedu').addClass('titcolor');  
			window.close();

			$('.accOculta').show();
		
		}, 1000);

	}
