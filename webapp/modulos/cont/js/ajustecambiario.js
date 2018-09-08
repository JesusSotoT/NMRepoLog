/**
 * @author Carmen Gutierrez
 */
function procesa(){
	var proceso = $('#proceso').val();
	var ejercicio = $('#ejercicio').val();
	var separa = $('#ejercicio').val().split('/');
	var periodo = $('#periodo').val();
	var utilidad = $('#utilidad').val();
	var perdida = $('#perdida').val();
	var tc = $('#tc').val();
	var moneda = $('#moneda').val();
	var idpoli=0;
	var sucursal = $('#sucursal').val();
	var segmento = $('#segmento').val();
	if(!tc){
		alert("Debe introducir el tipo de cambio, recuerde capturarlo en el menu 'Tipos de Cambio'");
		return false;
	}
	if(proceso==1){
		$('#load').show();
		$.post('ajax.php?c=ajustecambiario&f=validapoliza',{periodo:periodo,ejercicio:separa[0]},function (a){
			if(a!=0){
				if(confirm("La poliza ya existe desea reemplazarla?")){
					idpoli=a;
					$('#load').hide();
	
				}else{
					$('#load').hide();
					return false;
				}
			}else{
				idpoli=0;
			}
			//alert(a);
			$('#load').show();
			$.post('index.php?c=ajustecambiario&f=generapoliza',{
				proceso:proceso,
				ejercicio:ejercicio,
				periodo:periodo,
				utilidad:utilidad,
				perdida:perdida,
				tc:tc,
				moneda:moneda,
				idpoli:idpoli,
				sucursal:sucursal,
				segmento:segmento
			},function (resul){
				var respuesta=limpia(resul);
				var r=respuesta.split('-_-');
				if(r[1]==1){
					$('#load').hide();
					alert("Poliza generada correctamente!");
				}else if(respuesta==0){ $('#load').hide(); alert("Error al crear Poliza");	}
			});
		});
	}else if(proceso==2){
		window.location="index.php?c=ajustecambiario&f=generapoliza&proceso="+proceso+"&ejercicio="+ejercicio+"&periodo="+periodo+"&utilidad="+utilidad+"&perdida="+perdida+"&tc="+tc+"&moneda="+moneda;
	}
}
function limpia(val){
	var att = val.replace(/(<([^>]+)>)/ig,"");
	var a=att.replace(/\s/g,'');
	a=a.replace('2.1','');
	 a=a.replace('2.1','');
	 a=a.replace(/[A-Za-z\s]/g,'');
	 a=a.substr(1);
	 a.replace(/^\s+/,'').replace(/\s+$/,'');
 return a;
}
