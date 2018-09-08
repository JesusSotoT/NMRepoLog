<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<script>
var satd = 0; 
	$(document).ready(function(){
		

		$("#i2433").attr('onchange','return tipoconcepto(this.value)');//tipo concepto
		$("#i2431").attr('onchange','return especie(this.value)');//especie
		$("#i2432").attr('onchange','return sat()');//sat
		$("#i2464").append("<option value=-1 selected></option>");//pago 0
		$("#i2463").append("<option value=-1 selected></option>");//horas 0
		$("#i2432").append("<option value=-1 selected></option>");//sat 0
		
		tipoconcepto();
		//listado_percepciones_deducciones();
		sat();
		<?php if($_REQUEST['a'] != 0){?>
			$("#lbl2463,#lbl2464,#i2463_div,#i2464_div").hide();
		<? }else{ ?>
			// satd = $("#i2432 option:selected").val();
			// if(!satd){ satd=0;}
			// if($("#i2433").val()!=3){
				// $.post("../../modulos/nominas/ajax.php?c=Catalogos&f=listapercepdeduc&t="+$("#i2433").val(),//select yena tipo operacion
		         // function(data) 
		         // {alert(satd)
		         	// $("#i2432").empty();
		            // $("#i2432").html("<option value='0'>-----</option>");
		            // $("#i2432").append(data);
		            // $("#i2432").val(satd).select2({ width : "380px"});
		         // });
	        // }
      <?php } ?>
		

	});
	function sat(){
		
		if($("#i2433").val() == 1){
			if($("#i2432").val() == 16){ //si del listado selecciona horas extras
				$("#lbl2463,#i2463_div").show();//mostrara el catalogo de horas extras
				$("#i2463").val(1);
			}else{
				$("#lbl2463,#i2463_div").hide();
				$("#i2463 option[value=-1]").attr("selected",true);
			}
		}
	}
	function tipoconcepto(idtipo){
		$("#lbl2464,#i2464_div").hide();
		if($("#i2433").val() ==  1){//percepcion
			
			if($("#i2431").val() == -1){
				$("#lbl2432,#i2432_div").show();
				$("#lbl2464,#i2464_div").show();
			}else{
				$("#lbl2464,#i2464_div").hide();
				$("#i2464 option[value=-1]").attr("selected",true);
			}
			
		}else if($("#i2433").val() == 2){//deduccion
			$("#lbl2463,#i2463_div").hide();
			if($("#i2431").val() == -1){
				$("#lbl2432,#i2432_div").hide();
				$("#i2432,#i2463,#i2464 option[value=-1]").attr("selected",true);
			}else{
				$("#lbl2432,#i2432_div").show();
			}
			
		}else if($("#i2433").val() == 3){//obligacion
			$("#lbl2463,#i2463_div").hide();//siempre ocultara horas extrar si no es percepcion
			$("#lbl2432,#i2432_div").hide();
			$("#i2432,#i2463,#i2464 option[value=-1]").attr("selected",true);
		}
		
		listado_percepciones_deducciones();
	}
	function listado_percepciones_deducciones(){
		satd = $("#i2432").val();
			if(!satd){ 
				satd=0;
			}
		if($("#i2433").val()!=3){
			$.post("../../modulos/nominas/ajax.php?c=Catalogos&f=listapercepdeduc&t="+$("#i2433").val(),//select yena tipo operacion
	         function(data) 
	         {
	         	$("#i2432").empty();
	            $("#i2432").html("<option value='-1'>-----</option>");
	            $("#i2432").append(data);
	            $("#i2432").val(satd).select2({ width : "380px"});;
	         });
        }
	}
	function especie(idespecie){
		if( idespecie == -1 ){//si
			if( $("#i2433").val() == 1 ){
				$("#lbl2464,#i2464_div").show();
				$("#lbl2432,#i2432_div").show();//sat
				
			}else if($("#i2433").val() == 2){//deduccion
				$("#lbl2432,#i2432_div").hide();//sat
				$("#i2432,#i2463 option[value=-1]").attr("selected",true);
			}else if($("#i2433").val() == 3){//obligacion
				$("#lbl2432,#i2432_div").hide();//sat
				$("#i2432,#i2463 option[value=-1]").attr("selected",true);
			}
		}else{
			if( $("#i2433").val() == 1 ){
				$("#lbl2432,#i2432_div").show();//sat
				$("#lbl2464,#i2464_div").hide();//pago
				$("#i2464,#i2463 option[value=-1]").attr("selected",true);
			}else if($("#i2433").val() == 2){//deduccion
				$("#lbl2432,#i2432_div").show();//sat
				
			}else if($("#i2433").val() == 3){//obligacion
				$("#lbl2432,#i2432_div").hide();//sat
				$("#i2432,#i2463 option[value=-1]").attr("selected",true);
			}
		}
	}
</script>