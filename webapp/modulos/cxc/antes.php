<script>
             $(document).ready(function(){
            
                    $("#i309_1").attr("readonly",true);
                    $("#i309_2").attr("readonly",true);
                    $("#i309_3").attr("readonly",true);
                    $("#i309t").attr("readonly",true);
                    $("#i309_img").css("visibility","hidden");
                    $("#i297_1").attr("readonly",true);
                    $("#i297_2").attr("readonly",true);
                    $("#i297_3").attr("readonly",true);
                    $("#i297t").attr("readonly",true);
                    $("#i297_img").css("visibility","hidden");
                    $("#i316").removeAttr("alt");
                    $("#i316").attr("readonly",true);
                    $("#i317").removeAttr("alt");
                    $("#i317").attr("readonly",true);
                    $("#i315").css("display","none").after("<b style='font-size:16px;'>"+$('#i315').val()+"</b>");
                 
		
		
		
		
		
		$("#send").css('display','none').after("<input type='button' id='falseReg' value='Guardar'>");
		
		$("#falseReg").live('click',function(){
			if(parseFloat($("#i317").val()) < 0){
				alert("Los pagos no deben pueden ser mayores al saldo. Revise su formulario.");
			}else
			{
				$("#send").click();
			}
		});
		
		
	     var lista = $('#i310').children('option').get();
	     $('#i310').html(lista.sort())
            
             });
            
             
             
             </script>
             
             

             