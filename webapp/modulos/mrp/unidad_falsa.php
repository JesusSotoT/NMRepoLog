<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script>
	$(function(){
			$(document).ready(function() {
				$("input[onclick='btn_i424_click();']").hide();
	$('#send').hide();
	var lwpButton = $("<input>", { 
		id: "send2", 
		type:"button",
		value:"Guardar",
		title: "guardar",
        alt: "guardar",
        class: " nminputbutton ",
        click: function (e) {
		 	}
		 	  });
		 	   $("body").append(lwpButton);
		 	});
		<?php
			
		if($_REQUEST['a'] != 0)
			{
				
				//Interface de nuevo registro
		?>
		
			$(document).ready(function() {
	
	$('#send2').click(function() {
		var nombre=jQuery('#i407').val();
 $.post("../../modulos/mrp/js/unidad.php",{opc:1,nombre:nombre},
	 function(respues) {	
		if(respues=="si"){
		  alert("La unidad ya existe");	 
		  }else{
		  	 $('#send').click();
		  }
		  });
   	    });	
 	}); 
			    
			    
				//$("#i424").append("<option value=1>Si misma</option>");
				// $("#i424").change(function(){ 
					// if($("#i424").val() == 1)
					// { 
						// $("#i416").val("1234");
					// } else{
						// $("#i416").empty();
					// }
				// });
		<?php
			}
		else
			{
				//Interface de edicion
				
		?>
		$(document).ready(function() {

	
if($('#i407').val()=='Unidad'){ alert('Esta unidad no puede ser editada');
     $('#i407').prop('disabled', true);
     $('#i416').prop('disabled', true);
     $('#i424').prop('disabled', true);
     $('#same').prop('disabled', true);
  window.history.back();
 }else{
			$('#send2').click(function() {
			var nombre=jQuery('#i407').val();
		var id=jQuery('#i406').val();
			$.post("../../modulos/mrp/js/unidad.php",{opc:2,nombre:nombre},
			function(respues) {
			if(respues=="no"){
				
		  	 $('#send').click();
			}else{
				$.post("../../modulos/mrp/js/unidad.php",{opc:3,id:id,nombre:nombre},
			function(respuest) {
				//alert(respuest);
				if(respuest!="mismo"){
		  alert("La unidad ya existe");	 
		  }else{
		  	 $('#send').click();
		  }
				
			});
				 }
		    });
	     });
	     
	    }
     }); 
							$("#i424").prepend("<input type='hidden' id='prev_selection' value=" + $("#i424").val()+ ">" );
				$("#i416").prepend("<input type='hidden' id='prev_conversion' value=" + $("#i416").val()+ ">" );
		
		<?php
			}
			
			
		?>
			$("#lbl416").prepend("<br><input type='checkbox' id='same' name='same' value='1' onClick='funcion();' class=' nminputcheck ' >Equivale a si misma.<p/><p/>");
	});
	function funcion()
	{
		if($("#same").is(':checked')) 
		{  
			
		    <?php
			if($_REQUEST['a'] != 0)
			{
				//Interface de nuevo registro
			?>
				$("#i424").prepend("<input type='hidden' id='prev_selection' value=1234>" );
				$("#i416").prepend("<input type='hidden' id='prev_conversion' value=1234>" );
				
				$('#i416').prop('disabled', true);
			    $('#i424').prop('disabled', true);
			    
			    $('#i416').hide();
			    $('#i424').hide();
			    $('#lbl424').hide();
			    
			    $("input[onclick='btn_i424_click();']").css("visibility","hidden");
			    //$('#lbl416').empty();
			    //$('#lbl416').html("<br><input type='checkbox' id='same' name='same' value='1' onClick='funcion();' checked>Equivale a si misma.<p/><p/>");
			    
			    $("#i424").val(1);
			    $("#i416").val(1234);
			<?php
			}
			else
			{
				//Interface de edicion
			?>
				$("#prev_conversion").val($("#i416").val());
				$("#prev_selection").val($("#i424").val());
				
				$('#i416').prop('disabled', true);
			    $('#i424').prop('disabled', true);
			    
			    //$('#i416').css('visibility','hidden');
			    //$('#i424').css('visibility','hidden');
			    //$('#lbl424').css('visibility','hidden');
			    
			    $("input[onclick='btn_i424_click();']").css("visibility","hidden");
			    //$('#lbl416').empty();
			   // $('#lbl416').html("<br><input type='checkbox' id='same' name='same' value='1' onClick='funcion();' checked>Equivale a si misma.<p/><p/>");
			   
			    $("#i424").val($("#i406").val());
			    $("#i416").val('1');
			    
			<?php
			}
			?>
	    } 
	    else 
	    {  
	    	 <?php
			if($_REQUEST['a'] != 0)
			{
				//Interface de nuevo registro
			?>
				
		    	$("#i416").empty();
		       	$('#i416').prop('disabled', false);
		       	$('#i424').prop('disabled', false);
		       	
		       	$('#i416').show();
			   $('#i424').show();
			   $('#lbl424').show();
			   $("input[onclick='btn_i424_click();']").css("visibility","hidden");
			   //$("input[onclick='btn_i424_click();']").show();
			    
			   // $('#lbl416').empty();
			   // $('#lbl416').html("<br><input type='checkbox' id='same' name='same' value='1' onClick='funcion();' >Equivale a si misma.<p/><p/> Conversion:");
			     
		       	$("#i424").val($("#prev_selection").val());
		       	$("#i416").val($("#prev_conversion").val());
		    <?php
			}
			else
			{
				//Interface de edicion
			?>
				$("#i416").val('');
		       	$('#i416').prop('disabled', false);
		       	$('#i424').prop('disabled', false);
		       	
		       	$('#i416').css('visibility','visible');
			    $('#i424').css('visibility','visible');
			    $('#lbl424').css('visibility','visible');
			    //$("input[onclick='btn_i424_click();']").css("visibility","visible");
			    //$('#lbl416').empty();
			   // $('#lbl416').html("<br><input type='checkbox' id='same' name='same' value='1' onClick='funcion();' >Equivale a si misma.<p/><p/> Conversion:");
			     
		       	$("#i424").val($("#prev_selection").val());
		       	$("#i416").val($("#prev_conversion").val());
		       
			<?php
			}
			?>
	    }
	};
	
		
	
	
	
	
	
	
	
	
	
	
	
	
	$('#frm').submit(function() {
		
		$('#i416').prop('disabled', false);
		$('#i424').prop('disabled', false);
		
	    var cadena = $("#i384").val();
	    var alerta = "";
		if (cadena.length == 12)
			var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		else
			var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		
		var validRfc=new RegExp(valid);
		var matchArray=cadena.match(validRfc);
		
		if ($("#i390").val() == "" || $("#i389").val() == "")
			alerta += "- Seleccione primero estado y municipio.\n";
		if ($("#i383").val() == "")
			alerta += "- Ingrese razon social.\n";
		if (matchArray==null) 
			alerta += "- El RFC no es valido.";
		
		if(alerta != "")
		{
			$('#i416').prop('disabled', true);
			$('#i424').prop('disabled', true);
			alert (alerta);
			return false;
		}
	});
</script>