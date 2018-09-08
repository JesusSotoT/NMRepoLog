<script type="text/javascript">
	$(document).ready(function(){
		$("#i679").attr('onblur','validacodigopost()');
		
	});
	function validacodigopost(){
		codigo=$("#i679").val();
		if(codigo.match(/^0/)){
			alert("No puedes introducir ese Codigo Postal");
			$("#i679").focus();
			//return false;
		}
	}	

</script>	