function dias_periodo(NombreEjercicio,v)
{
	$.post("ajax.php?c=CaptPolizas&f=InicioEjercicio",
 		 {
    		NombreEjercicio: NombreEjercicio
  		 },
  		 function(data)
  		 {
  		 	if (v==1){
  		 		data=NombreEjercicio+"-01-01";
  		 	}
  		 	var cad = data.split("-");
			var fin;
			if($('#Periodo').val() == 13)
			{
				$('#inicio_mes').html('31-12-'+cad[0]);
				$('#fin_mes').html('31-12-'+cad[0]);
			}
			else
			{
				$('#inicio_mes').html(moment(cad[0]+'-'+cad[1]+'-'+cad[2]).add('months', $('#Periodo').val()-1).format('DD-MM-YYYY'));
				fin = moment(cad[0]+'-'+cad[1]+'-'+cad[2]).add('months', $('#Periodo').val()).format('YYYY-MM-DD');
				fin = moment(fin).subtract('days',1).format('DD-MM-YYYY');
				$('#fin_mes').html(fin);
			}
  		 	
  		 });
	
}	
function cambioPeriodo(per,NameEjercicio)
{
	var siPuede = 1;
	if($("#diferencia").val() != '0.00')
	{
		var c = confirm("El periodo actual no esta cuadrado, aun asi desea cambiar?");
		if(!c)
		{
			siPuede = 0;
		}
	}
	if(siPuede)
	{
	$.post("ajax.php?c=CaptPolizas&f=CambioEjerciciosession",
 		 {
    		Periodo: per,
    		NameEjercicio: NameEjercicio
  		 },
  		 function()
  		 {
  		 	location.reload();
  		 });
	}
}

function cambioEjercicio(per,ej)
{
	var siPuede = 1
	if($("#diferencia").val() != '0.00')
	{
		var c = confirm("El periodo actual no esta cuadrado, aun asi desea cambiar?");
		if(!c)
		{
			siPuede = 0;
		}
	}
	if(siPuede)
	{
	$.post("ajax.php?c=CaptPolizas&f=CambioEjerciciosession",
 		 {
 		 	Periodo: per,
    		NameEjercicio: ej
  		 },
  		 function()
  		 {
  		 	location.reload();
  		 });
	}
}
function periodoactual(){
	$.post("ajax.php?c=CaptPolizas&f=ejercicioactual",
 		 {},
  		 function ()
  		 {
  		 	alert("Establecido");
  		 	window.location.reload();
  		 });
	
}
function unSoloBanco(){
	if($("#unsolobanco").is(":checked")){
		$(".trUnsoloBanco").show("fold", 900);
		$(".trbancos").hide("slow");
		$(".classiva,.classieps,.trpagototal").hide();
		$("#unsolobanco").val(1);
	}else{
		$(".trbancos,.trUnsoloBanco").show("fold", 900);
		$(".classiva,.classieps,.trpagototal").show();
		$(".trUnsoloBanco").hide("slow");
		$("#unsolobanco").val(0);
	}
}
