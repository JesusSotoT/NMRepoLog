$(document).ready(function(){
     

	$("#aguinaldo").on('click', function() { 
      var btnguardar = $(this);
      btnguardar.button("loading");
      var table = $('#tablaaguinaldo').DataTable();
  		table.destroy();
    //alert($("#numnomina").val());
    $.post("ajax.php?c=Prenomina&f=calculoAguinaldo",{
      diasrestantes:$("#diasrestantes").val(),
      periodo: $("#periodo").val(),
      incidencias:$("#incidencias").val(),
      percep:$("#percep").val(),
      isr:$("#isr").val()
      },function(resp){
        $("#contenidop").html(resp);
        btnguardar.button('reset');
        
     	$('#tablaaguinaldo').DataTable( {
				"language": {
					"url": "js/Spanish.json"
				}
		});
      

      }
    );
    
   
    });
    $("#acumularaguinaldo").on('click', function() { 
      var btnguardar2 = $(this);
     // btnguardar.button("loading");
     
  if($('#tablaaguinaldo >tbody >tr').length > 1){
  	
  	$.post("ajax.php?c=Prenomina&f=acumulaAguinaldo",{
  		isr:$("#isr").val(),
  		percep:$("#percep").val(),
  		 periodo: $("#periodo").val()
  	},function (resp){
  		if(resp == 1){
  			alert("Acumulado");
  		}else if(resp == 4){//no hay periodo extraordinario
  			alert("Debe agregar un periodo extraordinario");
  		}else if(resp == 3){//existe acumulado del ano
  			alert("Ya realizo el calculo de aguinaldo del ejercicio vigente");
  		}else{//error acumulado
  			alert("Error en el proceso de acumulado intente de nuevo.");
  		}
  	});
  	
  }else{
  	alert("No existen datos calculados para entregar");
  	
  }
   
    });
});
function mandatablaempresa(){
	
	window.parent.agregatab("../../netwarelog/catalog/gestor.php?idestructura=419&ticket=testing","Antigüedades","",419);

}
function irConfiguracion(){
	window.parent.agregatab('../../modulos/nominas/index.php?c=Catalogos&f=configuracion','Configuracion','',2257);
	window.parent.preguntar=true;
 }
function diasrestan(){
	if( $("#diasrestantes").is(":checked") ){
		$("#diasrestantes").val(1);
	}else{
		$("#diasrestantes").val(0);
	}
}
