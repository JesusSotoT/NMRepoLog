$(function()
{
		$('.date-pick').datePicker({startDate:'01/01/2000'});
		cambia_fecha();
		if($('#period').val() == 'm')
		{
			cambia_periodo();
		}
		estructura_vacia();
});

function estructura_vacia()
{
	if($('#structure').val() == '')
		{
			$('#structure').val('999.9999');
		}
}

function cambia_periodo()
{
	var cadena=$('.date-pick').val();
	var cad = cadena.split("-");
	var fin;
	if($('#current_period').val() == 13)
		{
			$('#inicio_mes').html('31-12-'+cad[2]);
			$('#fin_mes').html('31-12-'+cad[2]);
		}
		else
		{
			$('#inicio_mes').html(moment(cad[2]+'-'+cad[1]+'-'+cad[0]).add('months', $('#current_period').val()-1).format('DD-MM-YYYY'));
			fin = moment(cad[2]+'-'+cad[1]+'-'+cad[0]).add('months', $('#current_period').val()).format('YYYY-MM-DD');
			fin = moment(fin).subtract('days',1).format('DD-MM-YYYY');
			$('#fin_mes').html(fin);
		}
}
function cambia_fecha()
	{
		var cadena=$('.date-pick').val();
		var cad = cadena.split("-");
		var dias;
		
	if(moment([parseInt(cad[2])+1]).isLeapYear() && parseInt(cad[1])>=3 || moment([parseInt(cad[2])]).isLeapYear() && parseInt(cad[1])<3)
		{//Validacion de año bisiesto
			dias=365;		
		}
		else
		{
			dias = 364;
		}
		
	$('#fecha_fin').val(moment(cad[2]+'-'+cad[1]+'-'+cad[0]).add('days', dias).format('DD-MM-YYYY'))
	cambia_periodo();
	}

function regresar()
{
	window.location = 'index.php?c=Config';
}

//------------------------- ** Validaciones ** ------------------------------

function validaciones(cont)
{  
 // primera comprobación 
  if(cont.rfc.value == '')
  { 
    // informamos del error 
    alert('Escribe tu RFC');
    // seleccionamos el campo incorrecto 
    cont.rfc.focus();
    return false;
  }
  
  if(cont.periods.value == '13')
  {
  	if(cont.current_period.value == '' || cont.current_period.value == 0 || cont.current_period.value > 13)
  	{
    	// informamos del error
    	alert('Escribe un periodo vigente');
    	// seleccionamos el campo incorrecto
    	cont.current_period.focus();
    	return false;
  	}	 
  }
}

function validar_let(e)
{ // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
	patron = /\d/; // Solo acepta números 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

