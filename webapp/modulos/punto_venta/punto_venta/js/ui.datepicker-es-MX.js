/* InicializaciÃ³n en espaÃ±ol para la extensiÃ³n 'UI date picker' para jQuery. */
/* Traducido por Vester (xvester@gmail.com). */
/* Modificado y corregido por Ultiminio Ramos GalÃ¡n uramos@gmail.com */
/* 2013-02-15 */
(function($) {
    $.datepicker.regional['es-MX'] = {
        renderer: $.ui.datepicker.defaultRenderer,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
        'Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
        dayNamesShort: ['Dom','Lun','Mar','Mie','Juv','Vie','Sab'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        dateFormat: 'dd/mm/yy',
        firstDay: 0,
        prevText: '&#x3c;Ant', 
        prevStatus: '',
        prevJumpText: '&#x3c;&#x3c;', 
        prevJumpStatus: '',
        nextText: 'Sig&#x3e;', 
        nextStatus: '',
        nextJumpText: '&#x3e;&#x3e;', 
        nextJumpStatus: '',
        currentText: 'Hoy', 
        currentStatus: '',
        todayText: 'Hoy', 
        todayStatus: '',
        clearText: '-', 
        clearStatus: '',
        closeText: 'Cerrar', 
        closeStatus: '',
        yearStatus: '', 
        monthStatus: '',
        weekText: 'Sm', 
        weekStatus: '',
        dayStatus: 'DD d MM',
        dateStatus: "Seleccionar DD, MM d",
        defaultStatus: '',
        altFormat: "DD, d 'de' MM 'de' yy",
        isRTL: false
    };
    $.extend($.datepicker.defaults, $.datepicker.regional['es-MX']);
})(jQuery);