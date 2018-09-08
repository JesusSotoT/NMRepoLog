<script language='javascript'>
$(document).ready(function(){
	$('#i1031').attr('onkeypress','return validar_num(event)')
	$('#i1281').attr('onkeypress','return validar_num(event)')
	$('#i1036').attr('onkeypress','return validar_let(event)')
	$('#i386').attr('onkeypress','return validar_let(event)')
});
function validar_num(e) { // 1
		tecla = (document.all) ? e.keyCode : e.which; // 2
		if (tecla==8) return true; // 3
		patron =/[A-Za-zñÑ\s]/; // 4
		te = String.fromCharCode(tecla); // 5
		return patron.test(te); // 6
	}

function validar_let(e) { // 1
		tecla = (document.all) ? e.keyCode : e.which; // 2
		if (tecla==8) return true; // 3
	patron = /\d/; // Solo acepta números 4
		te = String.fromCharCode(tecla); // 5
		return patron.test(te); // 6
	}
</script>