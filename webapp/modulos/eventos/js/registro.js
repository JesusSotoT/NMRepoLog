var registro = {
	init: function(){
		alert("Initializando registro.js");
	},
	saludo: function(){
		alert("El registro del participante fue exitoso");
		location.reload();
	},
	despedida: function(){
		alert("Babai");
	},
    grabar: function(idEvento, idAsistente, idEmpresa, dateFechaRegistro){
        registro = $.ajax({
            type: "POST",
            url: "registra_asistentes.php",
            async: true,
            data: {
                sidEvento: idEvento, 
                sidAsistente: idAsistente,
                sidEmpresa: idEmpresa,
                sdateFechaRegistro: dateFechaRegistro 
            }
        }).done(function(response){
            alert("El registro del participante fue exitoso");
            window.location.reload();
        });
    },
	modal_mostrar: function(){
        var slcEvento = $('#slcEvento').val();
        var slcEventoN = $('#slcEvento option:selected').text();
        var selAsistente = $('#selAsistente').val();
        var selAsistenteN = $('#selAsistente option:selected').text();
        var selEmpresa_factura = $('#selEmpresa_factura').val();
        var selEmpresa_facturaN = $('#selEmpresa_factura option:selected').text();
        var selFecha = $('#selFecha').val();
        $('#lblEvento').text(slcEventoN);
		$('#lblAsistente').text(selAsistenteN);
        $('#lblEmpresa_factura').text(selEmpresa_facturaN);
        $('#lblFecha').text(selFecha);
        $('#modalPago').dialog({buttons:
        [
        {
            text: "Continuar",
            class: 'btn btn-success col-xs-2 offsetbtn',
            click: function()
            {
                registro.grabar(slcEvento, selAsistente, selEmpresa_factura, selFecha);
            }
        },
        {
            text: "Cancelar",
            class: 'btn btn-danger col-xs-2',
            click: function()
            {
                registro.despedida();
                $(this).dialog("close");
            }
        }
        ],
        position: 'top',
        modal: true,
        width: '600px'
    });
	}
}