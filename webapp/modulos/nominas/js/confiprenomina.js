function dragStart(event) {
    event.dataTransfer.setData("Text", event.target.id);
}

function dragging(event) {
}

function allowDrop(event) {
    event.preventDefault();
}

function drop(event,id) {//al soltarlo
    event.preventDefault();
    var data = event.dataTransfer.getData("Text");
    event.target.appendChild(document.getElementById(data));
    //$("#"+id).attr("class","agrega"+id);
}

$(function() {
	$('#guardar').on('click', function() { 
		var btnguardar = $(this);
		btnguardar.button("loading");
		if(confirm("Al grabar la nueva confiduracion de la prenomina, los empleados despues de un filtro \nno estaran en la nueva configuracion desea grabar la nueva configuracion?")){
    			var num = ( $("div[data-role='asignados'] li").length);
    			
    			$.post("ajax.php?c=Catalogos&f=eliminaPrevios",{
    				omision: $("#omision").val()
    			},function callback(resp){
    			
	    			if(resp==1){
		    			 $("div[data-role='asignados'] li").each(function (index) {
		    			 	
		    			 	
				       		$.post("ajax.php?c=Catalogos&f=almacenaConceptosPrenomina",{
				       			idconcepto:$(this).val(),
				       			valor: $("#valor"+$(this).val()).val(),
				       			importe: $("#importe"+$(this).val()).val(),
				       			omision: $("#omision").val()
				       		},function callback(request){
				       			if(request == 1){
					       			num--;
					       			if(num == 0){
					       				btnguardar.button('reset');
					       				alert("Los conceptos se almacenaron con exito");
					       				window.location="index.php?c=Catalogos&f=configPrenomina";
					       			}
				       			}
				       		});
				      });
			      }else{
			      	btnguardar.button('reset');
       				alert("Error en el proceso intente de nuevo");
       				window.location.reload();
			      }
		      });
		}
	});
    		
	$('#obteneromision').on('click', function() { 
		var btn = $(this);
		btn.button("loading");
		$.post("ajax.php?c=Catalogos&f=conceptosPrenominaDefault",{
		},function callback(request){
			btn.button('reset');
			if(request==0){
				alert("No tiene almacenado una configuracion default");
			}else{
				$("#todos").empty();
				$("#todos").html(request);
			}
		});
	});
	
});


function valorconf(idconcepto){
	if( $("#valor"+idconcepto).is(":checked")){
		$("#valor"+idconcepto).val(1);
	}else{
		$("#valor"+idconcepto).val(0);
	}
}
function importeconf(idconcepto){
	if( $("#importe"+idconcepto).is(":checked")){
		$("#importe"+idconcepto).val(1);
	}else{
		$("#importe"+idconcepto).val(0);
	}
}
function omision(){
	if( $("#omision").is(":checked")){
		$("#omision").val(1);
	}else{
		$("#omision").val(0);
	}
}

