//INICIA GENERA PDF
function pdf(){
				/*	
				var anchopadre = document.getElementById("tabla_reporte").parent.style.width;
				alert(anchopadre);
				var anchotabla = document.getElementById("tabla_reporte").style.width;
			  var pleft = (anchopadre / 2)+(anchotabla/2);
				alert(pleft);
				*/
				

				var contenido_html = $("#imprimible").html();
				//contenido_html = contenido_html.replace(/\"/g,"\\\"");
				$("#contenido").text(contenido_html);
				$("#divpanelpdf").modal('show');
}
function generar_pdf(){
				$("#divpanelpdf").modal('hide');
				//$("#loading").fadeIn(500);
}
function cancelar_pdf(){
				$("#divpanelpdf").modal('hide');
}

function pdf_generado(){
				alert("OK");
}	
// TERMINA GENERA PDF
// COMIENZA GENERAR MAIL
function mail(){
				var msg = "Registre el correo electrónico a quién desea enviarle el reporte:";
				var a = prompt(msg,"@netwarmonitor.com");
				if(a!=null){
					var html_contenido_reporte;
					html_contenido_reporte = $("#imprimible").html();
					$("#loading").fadeIn(500);
					$("#divmsg").load("../../../webapp/netwarelog/repolog/mail.php?a="+a, {reporte:html_contenido_reporte});
				}
}	
// TERMINA GENERAR MAIL