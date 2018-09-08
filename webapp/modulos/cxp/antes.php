<script>
             $(document).ready(function(){
            
                    $("#i299_1").attr("readonly",true);
                    $("#i299_2").attr("readonly",true);
                    $("#i299_3").attr("readonly",true);
                    $("#i299t").attr("readonly",true);
                    $("#i299_img").css("visibility","hidden");
                    $("#i300_1").attr("readonly",true);
                    $("#i300_2").attr("readonly",true);
                    $("#i300_3").attr("readonly",true);
                    $("#i300t").attr("readonly",true);
                    $("#i300_img").css("visibility","hidden");
                    $("#i343").removeAttr("alt");
                    $("#i343").attr("readonly",true);
                    $("#i344").removeAttr("alt");
                    $("#i344").attr("readonly",true);
                    $("#i342").css("display","none").after("<b style='font-size:16px;'>"+$('#i342').val()+"</b>");
                 
		
		
		
		
		
		$("#send").css('display','none').after("<input type='button' id='falseReg' value='Guardar'>");
		
		$("#falseReg").live('click',function(){
			if(parseFloat($("#i344").val()) < 0){
				alert("Los pagos no deben pueden ser mayores al saldo. Revise su formulario.");
			}else
			{
				$("#send").click();
			}
		});
		
		
	     var lista = $('#i339').children('option').get();
	     $('#i339').html(lista.sort())
            
             });
            
             
             
             </script>
             
             

             