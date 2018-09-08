$(document).ready(function(){
	$("#cuenta").select2({
     width : "10px"
    });
    
    $("#documentosbancarios").select2({
     width : "10px"
    });
});

function informacion(){
	$.post("ajax.php?c=Traspaso&f=infoDocumento",{
		idDocumento:$('#documentosbancarios').val()
	},function (resp){
		var datos = resp.split("/");
		$("#folio").val(datos[0]);
		$('#importe').html(datos[1]);
		$("#concepto").val(datos[2]);
		$("#referencia").val(datos[3]);
	});
}
function creartraspaso(){
	alert($("radio[name='documento']").val());
	// $.post("ajax.php?c=Traspaso&f=crearTraspaso",{
		// iddestino:$("#cuenta").val(),
		// fechadestino:$("#fechadestino").val(),
		// concepto:$("#concepto").val(),
		// referencia:$("#referencia").val(),
		// importe:$("#importe").val(),
		// tipo:$("radio[name='documento']").val()
	// },function (resp){
// 		
	// });
}
