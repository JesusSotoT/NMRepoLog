<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<script>
	$(document).ready(function(){
		$("#i2546").attr('onchange','return tipoincidencia()');//tipo incidencia
		$("#i2548").attr('onchange','return derecho()');//derecho sueldo
		tipoincidencia();
		derecho();
	});
	
	function tipoincidencia(){
		if( $("#i2546").val()== 1 ){//destajos
			$("#lbl2547,#i2547").show();//label,input valor
		//	$("#lbl2548,#i2548").hide();//label,select derecho sueldo
			$("#i2548,#i2550").prop('disabled', 'disabled').select2({ width : "100px"});
			//.removeClass("nminputselect_boolean  select2-hidden-accessible");
			$("#lbl2549,#i2549").hide();//label,input porcentaje
			$("#lbl2551,#i2551_div").hide();//label,select considera
			//$("#lbl2550,#i2550").hide();//label,select septimo dia
			
		}else if ( $("#i2546").val()== 2 ){//dias
			$("#lbl2547,#i2547").hide();//label,input valor
			$("#lbl2548,#i2548").show();//label,select derecho sueldo
			//$("#lbl2550,#i2550").show();//label,select septimo dia
			$("#lbl2551,#i2551_div").show();//label,select considera
			$("#i2548,#i2550").prop('disabled',false).select2({ width : "100px"});


		}else if( $("#i2546").val()== 3 ){//horas
			$("#lbl2547,#i2547").hide();//label,input valor
			//$("#lbl2548,#i2548").show();//label,select derecho sueldo
			//$("#lbl2550,#i2550").hide();//label,select septimo dia
			$("#lbl2551,#i2551_div").hide();//label,select considera
			$("#i2548").prop('disabled',false).select2({ width : "100px"});
			$("#i2550").prop('disabled',true).select2({ width : "100px"});

		}
	}
	function derecho(){
		if( $("#i2548").val()== -1 ){
			$("#lbl2549,#i2549").show();//label,input porcentaje

		}else{
			$("#lbl2549,#i2549").hide();//label,input porcentaje
			$("#i2549").val(0).select2({ width : "380px"});;
		}
	}
	
</script>