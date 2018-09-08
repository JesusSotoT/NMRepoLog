<script>
$(function(){
	
	$.fn.disable = function() {
	    return this.each(function() {          
	      if (typeof this.disabled != "undefined") {
	        $(this).data('jquery.disabled', this.disabled);
	
	        this.disabled = true;
	      }
	    });
	};
	
	$.fn.enable = function() {
	    return this.each(function() {
	      if (typeof this.disabled != "undefined") {
	        this.disabled = $(this).data('jquery.disabled');
	      }
	    });
	};
	
	//Monto
	$("#i1067").keypress(function(){ $("#i1069").val($("#i1067").val() - $("#i1068").val()); });
	$("#i1067").keyup(function(){ $("#i1069").val($("#i1067").val() - $("#i1068").val()); });
	
	//Abono
	$("#i1068").keypress(function(){ $("#i1069").val($("#i1067").val() - $("#i1068").val()); });
	$("#i1068").keyup(function(){ $("#i1069").val($("#i1067").val() - $("#i1068").val()); });
	$("#i1068").val(0);
	
	//Saldo actual
	$("#i1069").disable();
	$("#i1069").val($("#i1067").val() - $("#i1068").val());
	
	//Agregar filas
	$("#[title='Agregar fila']").onclick(function()
	{
		var contadorfilas = $("#txt_filasdetalles").val();
		for(var i=1; i<=contadorfilas; i++)
		{
			$("#i1074_"+i).disable();
			if($("#i1074_"+i).val() == "" || $("#i1074_"+i).val() == $("#i1068").val())
			{
				$("#i1074_"+i).val($("#i1068").val());
			}
			else if(i>1)
			{
				var imenosuno = i-1;
				$("#i1074_"+i).val($("#i1074_"+imenosuno));
			}
		}
	});
	
});	
</script>