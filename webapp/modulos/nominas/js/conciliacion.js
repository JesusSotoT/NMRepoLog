$(document).ready(function(){
// $('#tableconci').DataTable.destroy();
$('#tableprinci').DataTable( {

	"lengthMenu": [10, 25, 50,100,500,1500,2000,5000],
	"language": {
		"url": "js/Spanish.json"
	}
} );

document.getElementById('file-input')
.addEventListener('change', leerArchivo, false);

TableExport.prototype.txt = {

	defaultClass: "txt",
	buttonContent: "Generar archivo para el SAT.",
	separator: "|",
	mimeType: "text/plain",
	fileExtension: ".txt"
};
$("#tableprinci").tableExport({
	scrollX: true,
	ignoreCols: [0,1,5,6,7,8,9], 
	position: 'well ',
	headings: false,                   
	footers: false,                     
	formats: ["txt"], 
	defaultClass: "txt",
	fileName: "id",                   
	bootstrap: true,
	trimWhitespace: false,                   
	ignoreCSS: ".tableexport-ignore" 
});

$(document).on('click', '.browse', function(){
	var file = $(this).parent().parent().parent().find('.file');
	file.trigger('click');
});
$(document).on('change', '.file', function(){
	$(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
});

function leerArchivo(e) {
	var archivo = e.target.files[0];
	if (!archivo) {
		return;
	}
	var lector = new FileReader();
	lector.onload = function(e) {
		var contenido = e.target.result;
		mostrarContenido(contenido);
	};
	lector.readAsText(archivo);
}

function mostrarContenido(contenido) {
	var elemento = document.getElementById('contenidoarchivo');
	elemento.innerHTML = contenido;
	actualizarColumnaResultadoSAT();
	guardarRespuesta();
}

$(document).on('change', ':file', function() {
	var input = $(this),
	numFiles = input.get(0).files ? input.get(0).files.length : 1,
	label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
	input.trigger('fileselect', [numFiles, label]);
});

$(':file').on('fileselect', function(event, numFiles, label) {

	var input = $(this).parents('.input-group').find(':text'),
	log = numFiles > 1 ? numFiles + ' files selected' : label;

	if( input.length ) {
		input.val(log);
	} else {
		if( log ) alert(log);
	}

});


function actualizarColumnaResultadoSAT(){

	var dat = Array(); 
	var a = $('#contenidoarchivo').val().split('\n');
	a.forEach(function(x) {
// console.log(x);
var valorfilas = x.trim().split('|');
//valorfilas.forEach(function(f){
	if(valorfilas.length >0){
		dat.push({
			"registro" : valorfilas[0],
			"RFC" : valorfilas[1],
			"resultadoSAT" : valorfilas[2]
		}); 
	}   
}); 

	$("#tableconci tr").each(function(){
//console.log($(this).find(".tdRFC").html());
var RFC = $(this).find(".tdRFC").html();
for(var i=0;i<dat.length;i++){
	if (dat[i].RFC == RFC ){
		$(this).find(".tdResultadoSAT").html(dat[i].resultadoSAT);
	}
} 
}); 
}

dtConci.destroy();
var dtConci= $("#tableconci").DataTable( {
	"destroy": true,
	"scrollX": true,
	"lengthMenu": [10, 25, 50,100,500,1500,2000,5000],
	"language": {
		"url": "js/Spanish.json"
	}
});


dtConci.on("draw", function(){
	actualizarColumnaResultadoSAT();

});

});


function guardarRespuesta(){
	// table.destroy();
	var table = $('#tableconci').DataTable({
		"scrollX": true,
		"lengthMenu": [10, 25, 50,100,500,1500,2000,5000],
		"language": {
			"url": "js/Spanish.json"
		}
	}); 

	var datosGuardar = Array(); 
	table.rows().eq(0).each( function ( index ) {
		var row = table.row( index ); 
		var dat =row.data();  
		datosGuardar.push({
			"idEmpleado": dat[0],
			"RFC": dat[4],
			"ResultadoSAT": dat[10] =="V"? "1" : dat[10]!=""? "0" : ""
		}); 
	} );  

	$.post("ajax.php?c=Sobrerecibo&f=guardarRespuestaSAT",{  
		tableData : JSON.stringify(datosGuardar)
	},function(resp){
// alert(resp);

});
}

// function conciliResp() {
// 	 $('#tableconci').DataTable.destroy();

// $("#tableconci").DataTable( {

// // "destroy": true,
// "scrollX": true,
// "lengthMenu": [10, 25, 50,100,500,1500,2000,5000],
// "language": {
// "url": "js/Spanish.json"
// }
// });
// }


