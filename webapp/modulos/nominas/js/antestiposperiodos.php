
<script src="js/select2/select2.min.js"></script>
<link   rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>

<script>

	$(document).ready(function(){

		$("#i2389").attr('onchange','return tipoperiodo(this.value)');//tipo resportes
				
	});

	function tipoperiodo(){
		//$("#lbl2393,#i2393_div").hide();
		
		if($("#i2389").val() == -1){	
        
        	$("#lbl2393,#i2393_div").enable();
			//$("#lbl2393,#i2393_div").show();
		}else{

			$("#lbl2393,#i2393_div").hide();
			//$("#i2393 option[value=0]").attr("selected",true);
		}	
	}


</script>