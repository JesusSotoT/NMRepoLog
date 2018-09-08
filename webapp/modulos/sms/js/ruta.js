/**
 * @author karmen
 */
function regreso(){
		window.location="../views/configrutas/listado_ofertas.php";
	}

	
function validaDatos(namerut,idtrans,client) {

	if(vacio(namerut, "Nombre Ruta") == false
		|| trans(idtrans)==false
		|| clientes(client) == false
				){
		return error;
	}
	else {
		return true;
	}			
}
  
function vacio(q, c) {  
	for ( i = 0; i < q.length; i++ ) {  
       	if (q.charAt(i) != " ") {  
	       	    return true; 
       	}  
    }
    error = 'Error En '+c+'. El Campo no debe estar vacio.';
	return false;
}

function trans(t){
if(t=="--- Eliga una Unidad ---"){
	error='No ha asigando ninguna unidad';
	return false;
}else{
	return true;
}
}


function clientes(c){
if(c==""){
	error='No ha seleccionado ningun cliente';
	return false;
}else{
	return true;
}
}

function showloader(){//abre
		$('body').css('overflow','hidden');
		$('#carga').css('display','block');//show();
	};
	
function hideRegis(){//cierra
		$('body').css('overflow','auto');
		$('#carga').hide();
	};